<?php
namespace App\Infrastructure\Repository\LoginRepository;

use App\Application\token\Token;
use App\Infrastructure\Repository\BirthdayRepository\BirthdayRepository;
use App\Infrastructure\Repository\SqlRepository\SqlRepository;

class LoginRepository {
    public function __construct(private SqlRepository $sql, private BirthdayRepository $birthdayRepository) {}

    public function login(int|string $login, string $pass) {
        if (!$login || !$pass) {
            return ['error' => ['summary' => 'Login ou Senha inválidos, tente novamente.', 'code' => 401]];
        }

        $login = mb_strtoupper($login);
        
        $user = $this->logar_com_x($login, $pass);
        try {
            return array_merge($user, ['Token' => Token::create()->generateToken($user)]);
        } catch (\Exception $e) {
            return ['error' => ['summary' => 'Não foi possível iniciar a sessão do usuário. Verifique se os cookies estão permitidos.', 'code' => 500]];
        }
    }

    private function logar_com_x(string $login, string $pass)
    {
        [$ldap_mail, $ldap_name, $ldap_login] = $this->logar_ldap($login, $pass);
        $usuario = $this->birthdayRepository->selectUserByLogin($login);

        if ($usuario === null) {
            $this->birthdayRepository->insert('usuarios', [
                'login_rede' => mb_strtoupper($ldap_login),
                'name' => $ldap_name,
                'email' => $ldap_mail,
            ]);
        }

        return [$ldap_mail, $ldap_name, $ldap_login];
    }
    private function logar_ldap(string $user, string $pass): array
    {
        global $env;

        $conn = @ldap_connect($env['LDAP_HOST']);
        if (!$conn) {
            // $this->log->loggerTelegram("ERRO-LDAP", "Falha na comunicação com o servidor Ldap");
            throw new \Exception("Falha na comunicação com o servidor! Tente novamente.");
        }

        ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldap = @ldap_bind($conn, $env['LDAP_DOMAIN'] . "\\" . $user, $pass);
        if (!$ldap) {
            // $this->log->loggerCSV("Erro-login", "Usuário $user tentou efetuar login com credenciais inválidas");
            throw new \Exception("Usuário ou senha inválidos! Verifique suas credenciais.");
        }

        $search = ldap_search($conn, $env['LDAP_BASE'], "sAMAccountName=$user");
        $info = ldap_get_entries($conn, $search);


        if (!isset($info[0]['mail'][0])) {
            // $this->log->loggerCSV("Erro-login", "Usuário $user tentou efetuar login com credenciais inválidas");
            throw new \Exception("Usuário ou senha inválidos! Verifique suas credenciais.");
        }

        $ldap_mail = $info[0]['mail'][0]; // email
        $ldap_name = $info[0]['cn'][0]; // nome do usuário
        $ldap_login = $info[0]['samaccountname'][0]; // login de rede
        return [$ldap_mail, $ldap_name, $ldap_login];
    }
}
<?php 
namespace App\classes;

require(__DIR__.".../../../vendor/setasign/fpdf/fpdf.php");

use fpdf;
use Exception;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


class ExportData 
{   
  use Helpers ;
public function excel(array $dados,string $title=''){

    if (empty($dados)) throw new Exception('Não ha dados para gerar o excel');
 
    try {

        $name = strtolower("Monit_".$title.'_'.str_replace('-','_',$GLOBALS['days']));

        $header = array_keys($dados[0]);
        $spreadsheet = new Spreadsheet();
        $activeSheet = $spreadsheet->getActiveSheet();
    
        $activeSheet->fromArray($header, null, 'A1');
    
        $row = 2; 
        foreach ($dados as $dado) {
            $col = 'A';
            foreach ($dado as $value) {
             
                $activeSheet->setCellValue("{$col}{$row}", $value);
                $col++; 
            }
            $row++; 
        }
        $activeSheet->setTitle($name);
    
        $writer = new Xlsx($spreadsheet);

        // $writer->save('php://output'); 
        //  $writer->save()

        $folder = __DIR__ ."/../../files/excel/";
        $name.='.xlsx';
        $writer->save("$folder$name");
        $dados = [
            $folder.$name,
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'attachment; filename="' . $name . '"',
            $name

        ];
        return $dados ; 
    } catch (\Throwable $th) {
        $this->logGenerate()->loggerCSV('Erro_gerar_excel',$th->getMessage());
        throw new Exception("Erro ao tentar baixar dados para planilha");
    }

}
public function pdf(array $dados, string $title = '', $verify_title = null)
    {

    try {
        if (empty($dados)) {
            throw new Exception('Não há dados para gerar o PDF');
        }

        // Nome do arquivo PDF
        match(true){
            is_null($verify_title) => $name = strtolower("Monit_" . $title . '_' . str_replace('-', '_', $GLOBALS['days']) . '.pdf'),
            !is_null($verify_title) => $name = strtolower($title . '_' . str_replace('-', '_', $GLOBALS['days']) . '.pdf')
        };
        $folder = __DIR__ . "/../../files/pdf/";

        $name;
        // Cria o cabeçalho da tabela
        $header = array_keys($dados[0]);
        
        // Inicializa o Dompdf
        $dompdf = new Dompdf();

        // Construção do HTML para o PDF
        match(true){
            is_null($verify_title) =>$html = '<h1 style="text-align:center;">Relatório de Monitoramento ' . ucwords(strtolower($title)). '</h1>',
            !is_null($verify_title) => $html = '<h1 style="text-align:center;">' . ucwords(strtolower($title)) . '</h1>'
        };
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse: collapse;">';
        
        // Adiciona o cabeçalho da tabela
        $html .= '<thead><tr>';
        foreach ($header as $coluna) {
            $html .= '<th style="background-color:#f2f2f2;">' . htmlspecialchars(strtoupper($coluna)) . '</th>';
        }
        $html .= '</tr></thead>';
        
        // Adiciona os dados à tabela
        $html .= '<tbody>';
        foreach ($dados as $linha) {
            $html .= '<tr>';
            foreach ($linha as $valor) {
                $html .= '<td>' . htmlspecialchars(mb_convert_encoding($valor, 'UTF-8')) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>';
        $html .= '</table>';
        
        // Carrega o HTML no Dompdf
        $dompdf->loadHtml($html);
        
        // Define o tamanho da página e a orientação (opcional)
        $dompdf->setPaper('A4', 'portrait');
        
        // Renderiza o HTML como PDF
        $dompdf->render();

        // Salva o arquivo PDF na pasta especificada
        file_put_contents($folder . $name, $dompdf->output());

        // Retorna os dados do arquivo PDF gerado
        return [
            "$folder$name",
            'application/pdf',
            'attachment; filename="' . $name . '"',
            $name
        ];

    } catch (\Throwable $th) {
        $this->logGenerate()->loggerCSV('Erro_dompdf_gerar_pdf', $th->getMessage());
        throw new Exception("Erro ao tentar baixar o PDF");
    }
    }

public function csv(array $dados, string $title = '') {

        if (empty($dados)) throw new Exception('Não há dados para gerar o csv');
    
        try {
            $name = strtolower("Monit_".$title.'_'.str_replace('-', '_', $GLOBALS['days']).'.csv');
    
            $header = array_keys($dados[0]);
            $spreadsheet = new Spreadsheet();
            $activeSheet = $spreadsheet->getActiveSheet();
            $activeSheet->fromArray($header, null, 'A1');
    
            $row = 2; 
            foreach ($dados as $dado) {
                $col = 'A'; 
                foreach ($dado as $value) {
                    // Converter o valor para ISO-8859-1 com fallback de transliteração
                    $activeSheet->setCellValue("{$col}{$row}", $value,);
                    $col++;
                }
                $row++;
            }
    
            $activeSheet->setTitle($name);
    
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(','); 
            $writer->setEnclosure('"'); 
            $writer->setLineEnding("\r\n"); 
            $writer->setSheetIndex(0); 
    
            $folder = __DIR__ . "/../../files/csv/";
            $tempFile = "$folder$name";
    
            // Salvar o arquivo temporariamente
            $writer->save($tempFile);
    
            // Ler o arquivo gerado e adicionar o BOM para garantir compatibilidade com Excel
            $csvContent = file_get_contents($tempFile);
    
            // Reescrever o arquivo com o conteúdo convertido para ISO-8859-1
            $bom = chr(0xEF) . chr(0xBB) . chr(0xBF); // UTF-8 BOM
            file_put_contents($tempFile, $bom . $csvContent);
    
            $dados = [
                "$folder$name",
                'text/csv',
                'attachment; filename="' . $name . '"',
                $name
            ];
    
            return $dados;
    
        } catch (\Throwable $th) {
            $this->logGenerate()->loggerCSV('Erro_gerar_csv', $th->getMessage());
            throw new Exception("Erro ao tentar baixar CSV");
        }
    }
    


}


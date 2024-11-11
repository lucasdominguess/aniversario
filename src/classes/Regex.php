<?php 
namespace App\classes;

class Regex 
/**
 * Classe contem Constantes com expressões regulares basicas 
 * 
 */

{
    
    CONST CPF = "/^\d{3}\.?\d{3}\.?\d{3}-?\d{2}$/im" ;
    CONST EMAIL_ROOT = "/^([\wàáâãçèéêìíîòóôõùúû'_.]{4,}@[\w]{5,10}\.(sp|com)(.gov)?(.br)?|root)$/im" ; 
    CONST PASSWORD = '/(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[$*&@#_])[0-9a-zA-Z$*&@#_]{8,19}/' ; 
    
    CONST NAME = "/^[a-zàáâãçèéêìíîòóôõùúû'\s]{3,}$/im" ; 

    CONST SITE = "/(https|http|www):?\/?\/?\w*\.\w*\.?\w{3}?(.br)?/im"; 

    CONST DATE = "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/im";
    CONST DATE_STRING = "/(0[1-9]|[12][0-9]|3[0-1])[ \/-]?(de)? ?(\d{2}|[A-Za-zç]{4,8})[- \/]?(de)? ?\d{4}/im";

    CONST PLACA = "/\W[A-Za-z]{3}-\d\w?\d{2}/im";

    CONST CEP = "/^\d{5}\-?\d{3}$ /im";

    CONST NUMBER_TEL = "/\(?(\d{3}|\d{2})\)?[ -.](\d{5}[ -.]\d{4})|\d{7}[ -.]?\d{4} /im";

    CONST ID_LOGIN = "/^(x|X|D|d)\d{6}$/";

    CONST NUMERO_INT = "/^\d+$/";

    
}

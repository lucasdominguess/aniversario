<?php 
namespace App\classes;

class RequestParams 
{
    // Parâmetros de data
    public const DATES = 'dates';
    public const DATA = 'data';
    public const DATE = 'date';
    public const SDATE = 'sdate';
    public const EDATE = 'edate';

    //parametros para ids
    public const ID = 'id';
    public const IDS = 'ids';
    public const UNIDADE = 'unidade';
    public const ID_UNIDADE = 'id_unidade';

    //inputs 
    public const E1 = 'E1';
    public const E2 = 'E2';
    public const E3 = 'E3';
    public const E4 = 'E4';
    public const E5 = 'E5';
    public const E6 = 'E6';
    public const E7 = 'E7';
    public const E8 = 'E8';
    public const E9 = 'E9';
    public const E10 = 'E10';
    public const E11 = 'E11';
    public const E12 = 'E12';
    public const E13 = 'E13';
    public const T1 = 'T1';
    public const T2 = 'T2';
    public const P1 = 'P1';
    public const P2 = 'P2';
    public const P3 = 'P3';


    // Método para retornar todos os parâmetros de data
    public static function DateFields(): array
    {
        return [
            self::DATES,
            self::DATA,
            self::DATE,
            self::SDATE,
            self::EDATE
        ];
    }
    public static function IdFields(): array
    {
        return [
            self::ID,
            self::IDS,
            self::ID_UNIDADE
        ];
    }
    public static function InputFields(): array
    {
        return [
            self::E1,
            self::E2,
            self::E3,
            self::E4,
            self::E5,
            self::E6,
            self::E7,
            self::E8,
            self::E9,
            self::E10,
            self::E11,
            self::E12,
            self::E13,
            self::T1,
            self::T2,
            self::P1,
            self::P2,
            self::P3
        ];
    }
    public static function AllFields(): array
    {
        return array_merge(self::DateFields(), self::IdFields());
    }
}

<?php

namespace App\DTOs;

class DetailTabel
{
    public ?string $idTabelUmum;
    public ?string $idCabang;
    public ?string $idTempat;
    public ?string $namaTabelUmum;
    public ?string $namaTempat;
    public ?string $namaCabang;
    public ?array $leads;
    public ?array $prospek;
    public ?array $hotProspek;
    public ?array $spk;
    public ?array $spkDo;

    public function __construct(
        ?string $idTabelUmum = null,
        ?string $idCabang = null,
        ?string $idTempat = null,
        ?string $namaTabelUmum = null,
        ?string $namaTempat = null,
        ?string $namaCabang = null,
        ?array $leads = [],
        ?array $prospek = [],
        ?array $hotProspek = [],
        ?array $spk = [],
        ?array $spkDo = []
    ) {
        $this->idTabelUmum = $idTabelUmum;
        $this->idCabang = $idCabang;
        $this->idTempat = $idTempat;
        $this->namaTabelUmum = $namaTabelUmum;
        $this->namaTempat = $namaTempat;
        $this->namaCabang = $namaCabang;
        $this->leads = $leads;
        $this->prospek = $prospek;
        $this->hotProspek = $hotProspek;
        $this->spk = $spk;
        $this->spkDo = $spkDo;
    }
}


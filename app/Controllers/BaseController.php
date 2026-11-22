<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * Seluruh 114 model dimuat secara lazy-loading via __get() agar setiap request
 * hanya menginstansiasi model yang benar-benar digunakan pada route tersebut.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation.
     *
     * @var array
     */
    protected $helpers = [];
    protected $startMicrotime = false;

    /**
     * Peta nama properti model => FQCN untuk lazy-loading (hemat CPU & RAM di setiap request).
     *
     * @var array<string, class-string>
     */
    private const MODEL_MAP = [
        'queryModel'                         => \App\Models\QueryModel::class,
        'linimasaModel'                      => \App\Models\Timeline\LinimasaModel::class,
        'roleAccessModel'                    => \App\Models\Master\RoleAccessModel::class,
        'userModel'                          => \App\Models\Master\UserModel::class,
        'unitKerjaModel'                     => \App\Models\Master\UnitKerjaModel::class,
        'dataPegawaiModel'                   => \App\Models\Master\DataPegawaiModel::class,
        'skemaTitimangsaModel'               => \App\Models\Master\SkemaTitimangsaModel::class,
        'golonganModel'                      => \App\Models\Master\GolonganModel::class,
        'jabatanModel'                       => \App\Models\Master\JabatanModel::class,
        'substansiModel'                     => \App\Models\Master\SubstansiModel::class,
        'kotaKabupatenModel'                 => \App\Models\Master\KotaKabupatenModel::class,
        'profilKantorModel'                  => \App\Models\Master\ProfilKantorModel::class,
        'timKerjaModel'                      => \App\Models\Master\TimKerjaModel::class,
        'manualBooksModel'                   => \App\Models\ManualBooksModel::class,
        'agendaUserModel'                    => \App\Models\AgendaUserModel::class,
        'emailLampiranModel'                 => \App\Models\EmailLampiranModel::class,
        'emailDataModel'                     => \App\Models\EmailDataModel::class,
        'notifikasiModel'                    => \App\Models\NotifikasiModel::class,
        'feedbackModel'                      => \App\Models\FeedbackModel::class,
        'infografisKapalModel'               => \App\Models\InfografisKapalModel::class,
        'infografisPesawatModel'             => \App\Models\InfografisPesawatModel::class,
        'infografisVaksinModel'              => \App\Models\InfografisVaksinModel::class,
        'cutiDataCutiModel'                  => \App\Models\CutiDataCutiModel::class,
        'cutiDataPermohonanModel'            => \App\Models\CutiDataPermohonanModel::class,
        'cutiSisaCutiModel'                  => \App\Models\CutiSisaCutiModel::class,
        'sipepesModel'                       => \App\Models\SipepesModel::class,
        'pegawaiModel'                       => \App\Models\PegawaiModel::class,
        'pesanModel'                         => \App\Models\PesanModel::class,
        'sipeditArsipModel'                  => \App\Models\SipeditArsipModel::class,
        'sipeditArsipKategoriModel'          => \App\Models\SipeditArsipKategoriModel::class,
        'sipeditArsipSubKategoriModel'       => \App\Models\SipeditArsipSubKategoriModel::class,
        'sinadiSettingModel'                 => \App\Models\SinadiSettingModel::class,
        'sinadiPerjalananDinasPesertaModel'  => \App\Models\SinadiPerjalananDinasPesertaModel::class,
        'sinadiPerjalananDinasModel'         => \App\Models\SinadiPerjalananDinasModel::class,
        'sinadiAksesModel'                   => \App\Models\SinadiAksesModel::class,
        'sinadiVerifikatorModel'             => \App\Models\SinadiVerifikatorModel::class,
        'sinadiVerifikatorApbnModel'         => \App\Models\SinadiVerifikatorApbnModel::class,
        'sinadiVerifikatorRplModel'          => \App\Models\SinadiVerifikatorRplModel::class,
        'sinadiLembarVerifikasiModel'        => \App\Models\SinadiLembarVerifikasiModel::class,
        'wbkAggotaModel'                     => \App\Models\WbkAnggotaModel::class,
        'wbkArsipModel'                      => \App\Models\WbkArsipModel::class,
        'wbkKategoriModel'                   => \App\Models\WbkKategoriModel::class,
        'wbkPokjaModel'                      => \App\Models\WbkPokjaModel::class,
        'wbkRencanaKerjaModel'               => \App\Models\WbkRencanaKerjaModel::class,
        'arkabJenisSuratModel'               => \App\Models\ArkabJenisSuratModel::class,
        'arkabKodeKlasifikasiModel'          => \App\Models\ArkabKodeKlasifikasiModel::class,
        'arkabSuratModel'                    => \App\Models\ArkabSuratModel::class,
        'relaxtimeRealisasiFileModel'        => \App\Models\RelaxtimeRealisasiFileModel::class,
        'relaxtimeRKTModel'                  => \App\Models\RelaxtimeRKTModel::class,
        'relaxtimeKROModel'                  => \App\Models\RelaxtimeKROModel::class,
        'relaxtimeROModel'                   => \App\Models\RelaxtimeROModel::class,
        'relaxtimeKomponenModel'             => \App\Models\RelaxtimeKomponenModel::class,
        'relaxtimeUraianModel'               => \App\Models\RelaxtimeUraianModel::class,
        'relaxtimeDetailUraianModel'         => \App\Models\RelaxtimeDetailUraianModel::class,
        'relaxtimeRealisasiModel'            => \App\Models\RelaxtimeRealisasiModel::class,
        'relaxtimeAksesModel'                => \App\Models\RelaxtimeAksesModel::class,
        'relaxtimeSettingModel'              => \App\Models\RelaxtimeSettingModel::class,
        'relaxtimeHasilRapatModel'           => \App\Models\RelaxtimeHasilRapatModel::class,
        'simandjaModel'                      => \App\Models\SimandjaModel::class,
        'simandjaTahunAnggaranModel'         => \App\Models\SimandjaTahunAnggaranModel::class,
        'simandjaAksesModel'                 => \App\Models\SimandjaAksesModel::class,
        'relaxtimeTargetBulananModel'        => \App\Models\RelaxtimeTargetBulananModel::class,
        'statusPegawaiModel'                 => \App\Models\StatusPegawaiModel::class,
        'uklwPasienModel'                    => \App\Models\UklwPasienModel::class,
        'uklwPemkesModel'                    => \App\Models\UklwPemkesModel::class,
        'uklwSkriningTBCModel'               => \App\Models\UklwSkriningTBCModel::class,
        'uklwSkriningHIVModel'               => \App\Models\UklwSkriningHIVModel::class,
        'uklwFaktorRisikoPTMModel'           => \App\Models\UklwFaktorRisikoPTMModel::class,
        'uklwICDSModel'                      => \App\Models\UklwICDSModel::class,
        'uklwCatatanPemeriksaanModel'        => \App\Models\UklwCatatanPemeriksaanModel::class,
        'uklwRiwayatPenyakitModel'           => \App\Models\UklwRiwayatPenyakitModel::class,
        'uklwLaboratoriumModel'              => \App\Models\UklwLaboratoriumModel::class,
        'uklwObatModel'                      => \App\Models\UklwObatModel::class,
        'uklwFarmasiModel'                   => \App\Models\UklwFarmasiModel::class,
        'uklwPengambilanObatModel'           => \App\Models\UklwPengambilanObatModel::class,
        'prlTppModel'                        => \App\Models\PrlTppModel::class,
        'prlSabModel'                        => \App\Models\PrlSabModel::class,
        'prlHsbuModel'                       => \App\Models\PrlHsbuModel::class,
        'prlInspeksiTppModel'                => \App\Models\PrlInspeksiTppModel::class,
        'prlInspeksiSabModel'                => \App\Models\PrlInspeksiSabModel::class,
        'prlInspeksiHsbuModel'               => \App\Models\PrlInspeksiHsbuModel::class,
        'siveraJentikModel'                  => \App\Models\SiveraJentikModel::class,
        'siveraLalatModel'                   => \App\Models\SiveraLalatModel::class,
        'siveraKecoaModel'                   => \App\Models\SiveraKecoaModel::class,
        'siveraTikusModel'                   => \App\Models\SiveraTikusModel::class,
        'siveraPinjalModel'                  => \App\Models\SiveraPinjalModel::class,
        'siveraAnophelesJentikModel'         => \App\Models\SiveraAnophelesJentikModel::class,
        'siveraAnophelesNyamukModel'         => \App\Models\SiveraAnophelesNyamukModel::class,
        'absJenisModel'                      => \App\Models\AbsJenisModel::class,
        'absLantaiModel'                     => \App\Models\AbsLantaiModel::class,
        'absRuangModel'                      => \App\Models\AbsRuangModel::class,
        'absLokasiModel'                     => \App\Models\AbsLokasiModel::class,
        'absImageModel'                      => \App\Models\AbsImageModel::class,
        'absExportLabelModel'                => \App\Models\AbsExportLabelModel::class,
        'absPengajuanPemindahanModel'        => \App\Models\AbsPengajuanPemindahanModel::class,
        'absPengajuanPemeliharaanModel'      => \App\Models\AbsPengajuanPemeliharaanModel::class,
        'lhkpnSptModel'                      => \App\Models\LhkpnSptModel::class,
        'absenModel'                         => \App\Models\AbsenModel::class,
        'absenJamModel'                      => \App\Models\AbsenJamModel::class,
        'absenZonaModel'                     => \App\Models\AbsenZonaModel::class,
        'absenTargetModel'                   => \App\Models\AbsenTargetModel::class,
        'absenTargetAksesModel'              => \App\Models\AbsenTargetAksesModel::class,
        'absenAksesModel'                    => \App\Models\AbsenAksesModel::class,
        'kendaraanKantorDaftarModel'         => \App\Models\KendaraanKantorDaftarModel::class,
        'kendaraanKantorRiwayatModel'        => \App\Models\KendaraanKantorRiwayatModel::class,
        'kendaraankantorVerifikatorModel'    => \App\Models\KendaraankantorVerifikatorModel::class,
        'pengawasanKapalModel'               => \App\Models\PengawasanKapal\PengawasanKapalModel::class,
        'pengawasanKapalAksesModel'          => \App\Models\PengawasanKapal\PengawasanKapalAksesModel::class,
        'pengawasanKapalTargetAksesModel'    => \App\Models\PengawasanKapal\PengawasanKapalTargetAksesModel::class,
        'sisoviPengalihanModel'              => \App\Models\Sisovi\SisoviPengalihanModel::class,
        'arsipTemplateNaskahModel'           => \App\Models\ArsipTemplateNaskahModel::class,
        'iframeModel'                        => \App\Models\IframeModel::class,
    ];

    /** @var array<string, mixed> */
    private array $loadedModels = [];

    public function __get(string $name)
    {
        if (array_key_exists($name, $this->loadedModels)) {
            return $this->loadedModels[$name];
        }
        if (isset(self::MODEL_MAP[$name])) {
            $class = self::MODEL_MAP[$name];
            return $this->loadedModels[$name] = new $class();
        }
        return null;
    }

    public function __set(string $name, $value): void
    {
        $this->loadedModels[$name] = $value;
    }

    public function __isset(string $name): bool
    {
        return array_key_exists($name, $this->loadedModels) || isset(self::MODEL_MAP[$name]);
    }

    protected function renderView(string $view, array $data = [])
    {
        if ($this->startMicrotime) {
            $data['start_time_microtime'] = $this->startMicrotime;
        }
        return view($view, $data);
    }

    protected function releaseSessionLock(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    /**
     * Constructor.
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);
        date_default_timezone_set('Asia/Bangkok'); // Added user timezone
    }
}

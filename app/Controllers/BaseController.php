<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

// Models
use App\Models\QueryModel;
use App\Models\Timeline\LinimasaModel;
use App\Models\Master\RoleAccessModel;
use App\Models\Master\UserModel;
use App\Models\Master\UnitKerjaModel;
use App\Models\Master\DataPegawaiModel;
use App\Models\Master\SkemaTitimangsaModel;
use App\Models\Master\GolonganModel;
use App\Models\Master\JabatanModel;
use App\Models\Master\SubstansiModel;
use App\Models\Master\KotaKabupatenModel;
use App\Models\Master\ProfilKantorModel;
use App\Models\Master\TimKerjaModel;
use App\Models\ManualBooksModel;
use App\Models\AgendaUserModel;
use App\Models\EmailLampiranModel;
use App\Models\EmailDataModel;
use App\Models\NotifikasiModel;
use App\Models\FeedbackModel;
use App\Models\InfografisKapalModel;
use App\Models\InfografisPesawatModel;
use App\Models\InfografisVaksinModel;
use App\Models\CutiDataCutiModel;
use App\Models\CutiDataPermohonanModel;
use App\Models\CutiSisaCutiModel;
use App\Models\SipepesModel;
use App\Models\PegawaiModel;
use App\Models\PesanModel;
use App\Models\SipeditArsipModel;
use App\Models\SipeditArsipKategoriModel;
use App\Models\SipeditArsipSubKategoriModel;
use App\Models\SinadiSettingModel;
use App\Models\SinadiPerjalananDinasPesertaModel;
use App\Models\SinadiPerjalananDinasModel;
use App\Models\SinadiAksesModel;
use App\Models\SinadiVerifikatorModel;
use App\Models\SinadiVerifikatorApbnModel;
use App\Models\SinadiVerifikatorRplModel;
use App\Models\SinadiLembarVerifikasiModel;
use App\Models\WbkAnggotaModel;
use App\Models\WbkArsipModel;
use App\Models\WbkKategoriModel;
use App\Models\WbkPokjaModel;
use App\Models\WbkRencanaKerjaModel;
use App\Models\ArkabJenisSuratModel;
use App\Models\ArkabKodeKlasifikasiModel;
use App\Models\ArkabSuratModel;
use App\Models\RelaxtimeRealisasiFileModel;
use App\Models\RelaxtimeRKTModel;
use App\Models\RelaxtimeKROModel;
use App\Models\RelaxtimeROModel;
use App\Models\RelaxtimeKomponenModel;
use App\Models\RelaxtimeUraianModel;
use App\Models\RelaxtimeDetailUraianModel;
use App\Models\RelaxtimeRealisasiModel;
use App\Models\RelaxtimeAksesModel;
use App\Models\RelaxtimeSettingModel;
use App\Models\RelaxtimeHasilRapatModel;
use App\Models\SimandjaModel;
use App\Models\SimandjaTahunAnggaranModel;
use App\Models\SimandjaAksesModel;
use App\Models\RelaxtimeTargetBulananModel;
use App\Models\StatusPegawaiModel;
use App\Models\UklwPasienModel;
use App\Models\UklwPemkesModel;
use App\Models\UklwSkriningTBCModel;
use App\Models\UklwSkriningHIVModel;
use App\Models\UklwFaktorRisikoPTMModel;
use App\Models\UklwICDSModel;
use App\Models\UklwCatatanPemeriksaanModel;
use App\Models\UklwRiwayatPenyakitModel;
use App\Models\UklwLaboratoriumModel;
use App\Models\UklwObatModel;
use App\Models\UklwFarmasiModel;
use App\Models\UklwPengambilanObatModel;
use App\Models\PrlTppModel;
use App\Models\PrlSabModel;
use App\Models\PrlHsbuModel;
use App\Models\PrlInspeksiTppModel;
use App\Models\PrlInspeksiSabModel;
use App\Models\PrlInspeksiHsbuModel;
use App\Models\SiveraJentikModel;
use App\Models\SiveraLalatModel;
use App\Models\SiveraKecoaModel;
use App\Models\SiveraTikusModel;
use App\Models\SiveraPinjalModel;
use App\Models\AbsJenisModel;
use App\Models\AbsLantaiModel;
use App\Models\AbsRuangModel;
use App\Models\AbsLokasiModel;
use App\Models\AbsImageModel;
use App\Models\AbsExportLabelModel;
use App\Models\AbsPengajuanPemindahanModel;
use App\Models\AbsPengajuanPemeliharaanModel;
use App\Models\LhkpnSptModel;
use App\Models\AbsenModel;
use App\Models\AbsenJamModel;
use App\Models\AbsenZonaModel;
use App\Models\AbsenTargetModel;
use App\Models\AbsenTargetAksesModel;
use App\Models\AbsenAksesModel;
use App\Models\KendaraanKantorDaftarModel;
use App\Models\KendaraanKantorRiwayatModel;
use App\Models\KendaraankantorVerifikatorModel;

use App\Models\PengawasanKapal\PengawasanKapalModel;
use App\Models\PengawasanKapal\PengawasanKapalAksesModel;
use App\Models\PengawasanKapal\PengawasanKapalTargetAksesModel;
use App\Models\Sisovi\SisoviPengalihanModel;

use App\Models\ArsipTemplateNaskahModel;
use App\Models\IframeModel;
/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
  /**
   * Instance of the main Request object.
   *
   * @var CLIRequest|IncomingRequest
   */
  protected $request;

  // Models
  protected $queryModel;
  protected $linimasaModel;
  protected $roleAccessModel;
  protected $userModel;
  protected $unitKerjaModel;
  protected $dataPegawaiModel;
  protected $skemaTitimangsaModel;
  protected $golonganModel;
  protected $jabatanModel;
  protected $substansiModel;
  protected $kotaKabupatenModel;
  protected $profilKantorModel;
  protected $timKerjaModel;
  protected $manualBooksModel;
  protected $agendaUserModel;
  protected $emailLampiranModel;
  protected $emailDataModel;
  protected $notifikasiModel;
  protected $feedbackModel;
  protected $infografisKapalModel;
  protected $infografisPesawatModel;
  protected $infografisVaksinModel;
  protected $cutiDataCutiModel;
  protected $cutiDataPermohonanModel;
  protected $cutiSisaCutiModel;
  protected $sipepesModel;
  protected $pegawaiModel;
  protected $pesanModel;
  protected $sipeditArsipModel;
  protected $sipeditArsipKategoriModel;
  protected $sipeditArsipSubKategoriModel;
  protected $sinadiSettingModel;
  protected $sinadiPerjalananDinasPesertaModel;
  protected $sinadiPerjalananDinasModel;
  protected $sinadiAksesModel;
  protected $sinadiVerifikatorModel;
  protected $sinadiVerifikatorApbnModel;
  protected $sinadiVerifikatorRplModel;
  protected $sinadiLembarVerifikasiModel;
  protected $wbkAggotaModel;
  protected $wbkArsipModel;
  protected $wbkKategoriModel;
  protected $wbkPokjaModel;
  protected $wbkRencanaKerjaModel;
  protected $arkabJenisSuratModel;
  protected $arkabKodeKlasifikasiModel;
  protected $arkabSuratModel;
  protected $relaxtimeRealisasiFileModel;
  protected $relaxtimeRKTModel;
  protected $relaxtimeKROModel;
  protected $relaxtimeROModel;
  protected $relaxtimeKomponenModel;
  protected $relaxtimeUraianModel;
  protected $relaxtimeDetailUraianModel;
  protected $relaxtimeRealisasiModel;
  protected $relaxtimeAksesModel;
  protected $relaxtimeSettingModel;
  protected $relaxtimeHasilRapatModel;
  protected $simandjaModel;
  protected $simandjaTahunAnggaranModel;
  protected $simandjaAksesModel;
  protected $relaxtimeTargetBulananModel;
  protected $statusPegawaiModel;
  protected $uklwPasienModel;
  protected $uklwPemkesModel;
  protected $uklwSkriningTBCModel;
  protected $uklwSkriningHIVModel;
  protected $uklwFaktorRisikoPTMModel;
  protected $uklwICDSModel;
  protected $uklwCatatanPemeriksaanModel;
  protected $uklwRiwayatPenyakitModel;
  protected $uklwLaboratoriumModel;
  protected $uklwObatModel;
  protected $uklwFarmasiModel;
  protected $uklwPengambilanObatModel;
  protected $prlTppModel;
  protected $prlSabModel;
  protected $prlHsbuModel;
  protected $prlInspeksiTppModel;
  protected $prlInspeksiSabModel;
  protected $prlInspeksiHsbuModel;
  protected $absJenisModel;
  protected $absLantaiModel;
  protected $absRuangModel;
  protected $absLokasiModel;
  protected $absImageModel;
  protected $absExportLabelModel;
  protected $absPengajuanPemindahanModel;
  protected $absPengajuanPemeliharaanModel;
  protected $lhkpnSptModel;
  protected $absenModel;
  protected $absenJamModel;
  protected $absenZonaModel;
  protected $absenTargetModel;
  protected $absenTargetAksesModel;
  protected $absenAksesModel;
  protected $kendaraanKantorDaftarModel;
  protected $kendaraanKantorRiwayatModel;
  protected $kendaraankantorVerifikatorModel;

  protected $pengawasanKapalModel;
  protected $pengawasanKapalAksesModel;
  protected $pengawasanKapalTargetAksesModel;
  protected $sisoviPengalihanModel;
  
  protected $arsipTemplateNaskahModel;
  protected $iframeModel;
  /**
   * An array of helpers to be loaded automatically upon
   * class instantiation. These helpers will be available
   * to all other controllers that extend BaseController.
   *
   * @var array
   */
  protected $helpers = [];
  protected $startMicrotime = false;

  protected function renderView(string $view, array $data = [])
  {
    if ($this->startMicrotime) {
      $data['start_time_microtime'] = $this->startMicrotime;
    }
    return view($view, $data);
  }
  /**
   * Be sure to declare properties for any property fetch you initialized.
   * The creation of dynamic property is deprecated in PHP 8.2.
   */
  // protected $session;

  /**
   * Constructor.
   */
  public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
  {
    // Do Not Edit This Line
    parent::initController($request, $response, $logger);
    date_default_timezone_set('Asia/Bangkok'); // Added user timezone

    $session = session();

    // Preload any models, libraries, etc, here.

    // E.g.: $this->session = \Config\Services::session();
    // Models
    $this->queryModel = new QueryModel();
    $this->linimasaModel = new LinimasaModel();
    $this->roleAccessModel = new RoleAccessModel();
    $this->userModel = new UserModel();
    $this->unitKerjaModel = new UnitKerjaModel();
    $this->dataPegawaiModel = new DataPegawaiModel();
    $this->skemaTitimangsaModel = new SkemaTitimangsaModel();
    $this->golonganModel = new GolonganModel();
    $this->jabatanModel = new JabatanModel();
    $this->substansiModel = new SubstansiModel();
    $this->kotaKabupatenModel = new KotaKabupatenModel();
    $this->profilKantorModel = new ProfilKantorModel();
    $this->timKerjaModel = new TimKerjaModel();
    $this->manualBooksModel = new ManualBooksModel();
    $this->agendaUserModel = new AgendaUserModel();
    $this->emailLampiranModel = new EmailLampiranModel();
    $this->emailDataModel = new EmailDataModel();
    $this->notifikasiModel = new NotifikasiModel();
    $this->feedbackModel = new FeedbackModel();
    $this->infografisKapalModel = new InfografisKapalModel();
    $this->infografisPesawatModel = new InfografisPesawatModel();
    $this->infografisVaksinModel = new InfografisVaksinModel();
    $this->cutiDataCutiModel = new CutiDataCutiModel();
    $this->cutiDataPermohonanModel = new CutiDataPermohonanModel();
    $this->cutiSisaCutiModel = new CutiSisaCutiModel();
    $this->sipepesModel = new SipepesModel();
    $this->pegawaiModel = new PegawaiModel();
    $this->pesanModel = new PesanModel();
    $this->sipeditArsipModel = new SipeditArsipModel();
    $this->sipeditArsipKategoriModel = new SipeditArsipKategoriModel();
    $this->sipeditArsipSubKategoriModel = new SipeditArsipSubKategoriModel();
    $this->sinadiSettingModel = new SinadiSettingModel();
    $this->sinadiPerjalananDinasPesertaModel = new SinadiPerjalananDinasPesertaModel();
    $this->sinadiPerjalananDinasModel = new SinadiPerjalananDinasModel();
    $this->sinadiAksesModel = new SinadiAksesModel();
    $this->sinadiVerifikatorModel = new SinadiVerifikatorModel();
    $this->sinadiVerifikatorApbnModel = new SinadiVerifikatorApbnModel();
    $this->sinadiVerifikatorRplModel = new SinadiVerifikatorRplModel();
    $this->sinadiLembarVerifikasiModel = new SinadiLembarVerifikasiModel();
    $this->wbkAggotaModel = new WbkAnggotaModel();
    $this->wbkArsipModel = new WbkArsipModel();
    $this->wbkKategoriModel = new WbkKategoriModel();
    $this->wbkPokjaModel = new WbkPokjaModel();
    $this->wbkRencanaKerjaModel = new WbkRencanaKerjaModel();
    $this->arkabJenisSuratModel = new ArkabJenisSuratModel();
    $this->arkabKodeKlasifikasiModel = new ArkabKodeKlasifikasiModel();
    $this->arkabSuratModel = new ArkabSuratModel();
    $this->relaxtimeRealisasiFileModel = new RelaxtimeRealisasiFileModel();
    $this->relaxtimeRKTModel = new RelaxtimeRKTModel();
    $this->relaxtimeKROModel = new RelaxtimeKROModel();
    $this->relaxtimeROModel = new RelaxtimeROModel();
    $this->relaxtimeKomponenModel = new RelaxtimeKomponenModel();
    $this->relaxtimeUraianModel = new RelaxtimeUraianModel();
    $this->relaxtimeDetailUraianModel = new RelaxtimeDetailUraianModel();
    $this->relaxtimeRealisasiModel = new RelaxtimeRealisasiModel();
    $this->relaxtimeAksesModel = new RelaxtimeAksesModel();
    $this->relaxtimeSettingModel = new RelaxtimeSettingModel();
    $this->relaxtimeHasilRapatModel = new RelaxtimeHasilRapatModel();
    $this->simandjaModel = new SimandjaModel();
    $this->simandjaTahunAnggaranModel = new SimandjaTahunAnggaranModel();
    $this->simandjaAksesModel = new SimandjaAksesModel();
    $this->relaxtimeTargetBulananModel = new RelaxtimeTargetBulananModel();
    $this->statusPegawaiModel = new StatusPegawaiModel();
    $this->uklwPasienModel = new UklwPasienModel();
    $this->uklwPemkesModel = new UklwPemkesModel();
    $this->uklwSkriningTBCModel = new UklwSkriningTBCModel();
    $this->uklwSkriningHIVModel = new UklwSkriningHIVModel();
    $this->uklwFaktorRisikoPTMModel = new UklwFaktorRisikoPTMModel();
    $this->uklwICDSModel = new UklwICDSModel();
    $this->uklwCatatanPemeriksaanModel = new UklwCatatanPemeriksaanModel();
    $this->uklwRiwayatPenyakitModel = new UklwRiwayatPenyakitModel();
    $this->uklwLaboratoriumModel = new UklwLaboratoriumModel();
    $this->uklwObatModel = new UklwObatModel();
    $this->uklwFarmasiModel = new UklwFarmasiModel();
    $this->uklwPengambilanObatModel = new UklwPengambilanObatModel();
    $this->prlTppModel = new PrlTppModel();
    $this->prlSabModel = new PrlSabModel();
    $this->prlHsbuModel = new PrlHsbuModel();
    $this->prlInspeksiTppModel = new PrlInspeksiTppModel();
    $this->prlInspeksiSabModel = new PrlInspeksiSabModel();
    $this->prlInspeksiHsbuModel = new PrlInspeksiHsbuModel();
    $this->siveraJentikModel = new SiveraJentikModel();
    $this->siveraLalatModel = new SiveraLalatModel();
    $this->siveraKecoaModel = new SiveraKecoaModel();
    $this->siveraTikusModel = new SiveraTikusModel();
    $this->siveraPinjalModel = new SiveraPinjalModel();
    $this->absJenisModel = new AbsJenisModel();
    $this->absLantaiModel = new AbsLantaiModel();
    $this->absRuangModel = new AbsRuangModel();
    $this->absLokasiModel = new AbsLokasiModel();
    $this->absImageModel = new AbsImageModel();
    $this->absExportLabelModel = new AbsExportLabelModel();
    $this->absPengajuanPemindahanModel = new AbsPengajuanPemindahanModel();
    $this->absPengajuanPemeliharaanModel = new AbsPengajuanPemeliharaanModel();
    $this->lhkpnSptModel = new LhkpnSptModel();
    $this->absenModel = new AbsenModel();
    $this->absenJamModel = new AbsenJamModel();
    $this->absenZonaModel = new AbsenZonaModel();
    $this->absenTargetModel = new AbsenTargetModel();
    $this->absenTargetAksesModel = new AbsenTargetAksesModel();
    $this->absenAksesModel = new AbsenAksesModel();
    $this->kendaraanKantorDaftarModel = new KendaraanKantorDaftarModel();
    $this->kendaraanKantorRiwayatModel = new KendaraanKantorRiwayatModel();
    $this->kendaraankantorVerifikatorModel = new KendaraankantorVerifikatorModel();
    
    $this->pengawasanKapalModel = new PengawasanKapalModel();
    $this->pengawasanKapalAksesModel = new PengawasanKapalAksesModel();
    $this->pengawasanKapalTargetAksesModel = new PengawasanKapalTargetAksesModel();
    $this->sisoviPengalihanModel = new SisoviPengalihanModel();
    
    $this->arsipTemplateNaskahModel = new ArsipTemplateNaskahModel();
    $this->iframeModel = new IframeModel();
  }
}

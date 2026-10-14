-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 15, 2023 at 09:20 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sicute`
--

-- --------------------------------------------------------

--
-- Table structure for table `cuti_data_cuti`
--

CREATE TABLE `cuti_data_cuti` (
  `id_cuti_data_cuti` int(11) NOT NULL,
  `id_data_pegawai` int(11) NOT NULL,
  `id_skema_titimangsa` varchar(64) NOT NULL,
  `id_unit_kerja` int(11) NOT NULL,
  `id_cuti_status_kepegawaian` int(11) NOT NULL,
  `id_cuti_status_atasan` int(11) NOT NULL,
  `id_cuti_status_pejabat` int(11) NOT NULL,
  `jenis_cuti` varchar(50) NOT NULL,
  `alasan_cuti` varchar(256) NOT NULL,
  `tanggal_awal` varchar(256) NOT NULL,
  `tanggal_akhir` varchar(256) NOT NULL,
  `tanggal_pengajuan` varchar(25) NOT NULL,
  `total_hari` varchar(64) NOT NULL,
  `alamat_cuti` varchar(256) NOT NULL,
  `no_telp` varchar(15) NOT NULL,
  `ket_cuti_validation_kepegawaian` varchar(256) NOT NULL,
  `ket_cuti_validation_atasan` varchar(256) NOT NULL,
  `ket_cuti_validation_pejabat` varchar(256) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cuti_data_cuti`
--

INSERT INTO `cuti_data_cuti` (`id_cuti_data_cuti`, `id_data_pegawai`, `id_skema_titimangsa`, `id_unit_kerja`, `id_cuti_status_kepegawaian`, `id_cuti_status_atasan`, `id_cuti_status_pejabat`, `jenis_cuti`, `alasan_cuti`, `tanggal_awal`, `tanggal_akhir`, `tanggal_pengajuan`, `total_hari`, `alamat_cuti`, `no_telp`, `ket_cuti_validation_kepegawaian`, `ket_cuti_validation_atasan`, `ket_cuti_validation_pejabat`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 5, '5|4|2', 2, 2, 2, 2, 'Cuti Tahunan', 'Liburan', '2028/01/01', '2028/01/03', '2028/01/01', '3', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', 'Mantaps', 'Lanjut', 'Selamat melangsungkan cuti\r\n', 9, 9, '2023-02-28 15:43:46', '2023-03-06 07:48:08'),
(6, 3, '5|4|2', 2, 2, 2, 2, 'Cuti Tahunan', 'Alasan saya', '2001/01/03', '2001/01/03', '2001/01/03', '1', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', 'Mantaps', 'Lanjut', 'Selamat melangsungkan cuti', 2, 2, '2023-03-01 14:21:23', '2023-03-01 14:21:23'),
(7, 3, '5|4|2', 2, 2, 2, 1, 'Cuti Tahunan', 'Ada aja alasannya', '2023/03/01|2023/03/02|2023/03/13', '2023/03/03|2023/03/02|2023/03/24', '2023/03/03', '3|1|10', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', '', 'Lanjut', '', 2, 2, '2023-03-02 13:30:49', '2023-03-06 14:01:16'),
(8, 3, '5|4|2', 2, 4, 1, 1, 'Cuti Tahunan', 'Ada aja', '2023/03/01|2023/03/06', '2023/03/03|2023/03/10', '2023/03/01', '3|5', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', 'Sudah Tidak BIsa Mengajukan Cuti Tahunan', '', '', 2, 2, '2023-03-02 14:49:53', '2023-03-03 14:34:32'),
(9, 5, '5|4|2', 2, 2, 2, 1, 'Cuti Sakit', 'Sakit Perut', '2023/03/01', '2023/03/02', '2023/03/03', '2', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', '0', 'Lanjut\r\n', '', 2, 2, '2023-03-07 10:17:53', '2023-03-10 14:04:54'),
(10, 3, '5|4|2', 2, 2, 1, 1, 'Cuti Tahunan', 'sederhana', '2023/03/10|2023/03/13', '2023/03/10|2023/03/17', '2023/03/10', '1|5', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', 'asa', '', '', 2, 2, '2023-03-10 14:05:55', '2023-03-10 14:14:48'),
(11, 6, '5|4|2', 2, 3, 1, 1, 'Cuti Tahunan', 'Nikah', '2023/03/12', '2023/03/12', '2023/03/12', '1', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', 'Ada beberapa tanggal yang sama saat pengajuan cuti, coba cek kembali', '', '', 10, 10, '2023-03-12 03:45:14', '2023-03-13 09:39:59'),
(12, 4, '5|4|2', 2, 2, 1, 1, 'Cuti Besar', 'sdfsd', '2023/03/03|2023/03/01|2023/03/01|2023/03/20|2023/03/01|2023/03/12', '2023/03/03|2023/03/03|2023/03/10|2023/03/23|2023/03/03|2023/03/12', '2023/03/12', '1|3|8|4|3|', 'JL. DR. MUARDI, GG. SWADYA 7, RT/RW 003/002, Kel/Desa BOJONGHERANG, Kecamatan CIANJUR', '089503916789', '-', '', '', 2, 2, '2023-03-12 03:45:49', '2023-03-13 10:37:35');

-- --------------------------------------------------------

--
-- Table structure for table `cuti_data_permohonan`
--

CREATE TABLE `cuti_data_permohonan` (
  `id_cuti_data_permohonan` int(11) NOT NULL,
  `id_data_pegawai` int(11) NOT NULL,
  `id_skema_titimangsa` varchar(64) NOT NULL,
  `id_unit_kerja` int(11) NOT NULL,
  `id_cuti_status_kepegawaian` int(11) NOT NULL,
  `id_cuti_status_atasan` int(11) NOT NULL,
  `jenis_permohonan` varchar(50) NOT NULL,
  `alasan_permohonan` varchar(256) NOT NULL,
  `tanggal` varchar(25) NOT NULL,
  `ket_permohonan_validation_kepegawaian` varchar(256) NOT NULL,
  `ket_permohonan_validation_atasan` varchar(256) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cuti_data_permohonan`
--

INSERT INTO `cuti_data_permohonan` (`id_cuti_data_permohonan`, `id_data_pegawai`, `id_skema_titimangsa`, `id_unit_kerja`, `id_cuti_status_kepegawaian`, `id_cuti_status_atasan`, `jenis_permohonan`, `alasan_permohonan`, `tanggal`, `ket_permohonan_validation_kepegawaian`, `ket_permohonan_validation_atasan`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(5, 2, '5|4', 2, 1, 1, 'Tidak Masuk Kerja', 'Saya ingin libur', '07 March 2023', '', '', 2, 2, '2023-03-07 08:16:43', '2023-03-13 04:20:13'),
(6, 3, '5|4', 2, 4, 1, 'Pulang Sebelum Waktunya', 'Ada keperluan mendadak', '08 March 2023', '', '', 9, 9, '2023-03-07 10:11:31', '2023-03-07 10:58:43'),
(7, 5, '5|4', 2, 2, 2, 'Pulang Sebelum Waktunya', 'Ada Keperluan Mendadak', '24 March 2023', '', '', 9, 9, '2023-03-07 10:12:00', '2023-03-07 11:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `cuti_status`
--

CREATE TABLE `cuti_status` (
  `id_cuti_status` int(11) NOT NULL,
  `status` varchar(16) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `cuti_status`
--

INSERT INTO `cuti_status` (`id_cuti_status`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Pending', '2023-02-26 12:55:10', '2023-02-26 12:55:10'),
(2, 'Disetujui', '2023-02-26 12:55:10', '2023-02-26 12:55:10'),
(3, 'Dikembalikan', '2023-02-26 12:55:10', '2023-02-26 12:55:10'),
(4, 'Ditolak', '2023-02-26 12:55:10', '2023-02-26 12:55:10');

-- --------------------------------------------------------

--
-- Table structure for table `data_pegawai`
--

CREATE TABLE `data_pegawai` (
  `id_data_pegawai` int(11) NOT NULL,
  `id_user` int(11) NOT NULL,
  `id_unit_kerja` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `jabatan` varchar(128) NOT NULL,
  `nip` varchar(18) NOT NULL,
  `golongan` varchar(10) NOT NULL,
  `masa_kerja` varchar(15) NOT NULL,
  `tgl_awal_masa` datetime DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `data_pegawai`
--

INSERT INTO `data_pegawai` (`id_data_pegawai`, `id_user`, `id_unit_kerja`, `nama`, `jabatan`, `nip`, `golongan`, `masa_kerja`, `tgl_awal_masa`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 6, 2, 'drg. RESI ARISANDI, MM, MH', 'Kepala Kantor', '197306292002121003', 'IV/b', '10|0|10', NULL, 2, 2, '2023-02-09 00:56:21', '2023-02-09 14:56:21'),
(3, 7, 3, 'Contoh', 'contoh', '123456789012345678', 'Contoh', '2|0|0', NULL, 6, 6, '2023-02-13 14:21:20', '2023-02-13 14:21:20'),
(4, 8, 2, 'Wartoni, SKM, MPH,', 'Kepala Subbagian Administrasi Umum', '197606191999031001', 'IV/a', '10|10|10', '0000-00-00 00:00:00', 2, 2, '2023-02-21 14:32:47', '2023-02-21 14:32:47'),
(5, 9, 2, 'Nuning Yayuk Wulandari', 'Analis Kepegawaian', '198408022006042002', 'III/b', '16|16|16', '0000-00-00 00:00:00', 2, 2, '2023-02-28 15:29:51', '2023-02-28 15:29:51'),
(6, 10, 2, 'YANI DWIYULI SETIANI, SKM, MKM', 'Epidemiolog Kesehatan Ahli Madya (JF)', '197107301993032001', 'IV/b', '29|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(7, 11, 2, 'dr. AMRIYAH SUCI NURANI', 'Dokter Ahli Madya (JF)', '197605202005012003', 'IV/a', '18|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(8, 12, 2, 'dr. ELLY FARIDA', 'Dokter (JP)', '197407022006042013', 'IV/a', '16|10|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(9, 13, 2, 'FITRI MAYAWATI, SKM', 'Epidemiolog Kesehatan Ahli Madya (JF)', '197111191998032001', 'IV/a', '24|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(10, 14, 2, 'dr. MEDI NURSASIH', 'Dokter Ahli Muda (JF)', '196605072009121001', 'IV/a', '13|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(11, 15, 2, 'H. EDDY HARIANTO, SKM, M.Epid', 'Epidemiolog Kesehatan Ahli (JP)', '198006302006041003', 'IV/a', '16|10|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(12, 16, 2, 'AGUS SUTOMO, SKM', 'Entomolog Kesehatan Ahli Muda (JF)', '196808181992031002', 'III/d', '30|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(13, 17, 2, 'BOYKE TANAKA, SKM', 'Sanitarian Ahli Muda (JF)', '196610121988031005', 'III/d', '34|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(14, 18, 2, 'TUKITNO, SKM', 'Sanitarian Ahli / Pemeriksa Sanitasi (JP)', '196903081991031005', 'III/d', '31|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(15, 19, 2, 'MULDIE, SKM', 'Epidemiolog Kesehatan Ahli (JP)', '197209051997031004', 'III/d', '25|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(16, 20, 2, 'SUBHAN ZAENI, SAP', 'Arsiparis / Pranata Kearsipan (JP)', '196810171992031003', 'III/d', '30|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(17, 21, 2, 'dr. NANANG LIONCU', 'Dokter Ahli Muda (JF)', '198103152010121003', 'III/d', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(18, 22, 2, 'YUSTIKAWATI, SKM, MKM', 'Epidemiolog Kesehatan Ahli (JP)', '197310272008012010', 'III/d', '15|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(19, 23, 2, 'LIANA RICA MON VIA, SKM', 'Epidemiolog Kesehatan Ahli (JP)', '198410072008012012', 'III/d', '15|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(20, 24, 2, 'TEGUH DHIKA ROHKUSWARA, SKM, M.Epid', 'Epidemiolog Kesehatan Ahli Muda (JF)', '198706292008121001', 'III/d', '14|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(21, 25, 2, 'RIFI ADI SUCIPTO, SKM, MKM', 'Epidemiolog Kesehatan Ahli Muda (JF)', '198509082010121002', 'III/d', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(22, 26, 2, 'NURUL AFIFA, S.Si.T., M.Keb., MH.Kes.', 'Epidemiolog Kesehatan Ahli Muda (JF)', '197712202008012012', 'III/d', '15|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(23, 27, 2, 'BENEDECTUS BAYU SABDO KUSUMO, SKM, MKM', 'Sanitarian Ahli Muda (JF)', '197609282005011001', 'III/d', '18|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(24, 28, 2, 'MARIATI SINAGA', 'Perawat Penyelia (JF)', '196806261997032002', 'III/d', '25|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(25, 29, 2, 'MUGA MULYA, SAP', 'Arsiparis Ahli / Penyuluh Kearsipan (JP)', '197212251997031001', 'III/d', '25|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(26, 30, 2, 'J U H A N A', 'Sanitarian / Pengelola Penyehatan Lingkungan (JP)', '197306081995031002', 'III/c', '27|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(27, 31, 2, 'YENI SURYAMAH, SKM, M.Epid', 'Epidemiolog Kesehatan Ahli Muda (JF)', '197406142009022001', 'III/c', '14|0|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(28, 32, 2, 'PUTU DESY SUSANTHI, SKM', 'Epidemiolog Kesehatan Ahli Muda (JF)', '198412212009022001', 'III/c', '14|0|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(29, 33, 2, 'INU WISNU', 'Sanitarian / Pengelola Penyehatan Lingkungan (JP)', '196806141999031001', 'III/c', '23|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(30, 34, 2, 'YANI SALAM, S.Si', 'Pengelola Barang Milik Negara (JP)', '197802202006042003', 'III/c', '16|10|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(31, 35, 2, 'dr. ARDINA CAHYA WIYANTI', 'Dokter Ahli Muda (JF)', '197308052014122002', 'III/c', '8|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(32, 36, 2, 'dr. WIDY ASTUTI', 'Dokter Ahli Muda (JF)', '198205212014122001', 'III/c', '8|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(33, 37, 2, 'DUDY AFFIANDY, SKM., M.Si', 'Entomolog Kesehatan Ahli Muda (JF)', '197905202006041009', 'III/c', '16|10|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(34, 38, 2, 'RAHMAWATI JATI MURWANI', 'Pranata Laboratorium Kesehatan (JP)', '198007102003122002', 'III/c', '19|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(35, 39, 2, 'SIRA ELKO, S.K.M', 'Sanitarian Ahli Muda (JF)', '198304062008121003', 'III/c', '14|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(36, 40, 2, 'KEKE RISKAWATI, SKM', 'Epidemiolog Kesehatan Ahli Muda (JF)', '199106282015032004', 'III/c', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(37, 41, 2, 'MARLIAH SANTI HR, SKM', 'Entomolog Kesehatan Ahli Muda (JF)', '197508232002122003', 'III/c', '20|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(38, 42, 2, 'DEDEH SUPRIATIN, AMK', 'Perawat Penyelia (JF)', '197708132001122001', 'III/c', '21|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(39, 43, 2, 'KIKI ATANTIO BAGIYADI', 'Analis Kepegawaian Mahir (JF)', '198202272005011001', 'III/b', '18|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(40, 44, 2, 'MOH. IMANUDDIN SALAM, SKM', 'Epidemiolog Kesehatan Ahli Pertama (JF)', '198404172006041002', 'III/b', '16|10|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(41, 45, 2, 'AMANDA CHERKAYANI SEJATI, SKM, MPH', 'Epidemiolog Kesehatan Ahli Pertama (JF)', '199206092015032005', 'III/b', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(42, 46, 2, 'DETIANTI SRI IRAWATI, SE', 'Analis Pengelolaan Keuangan APBN Ahli Pertama (JF)', '198503302015032002', 'III/b', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(43, 47, 2, 'IGNATIUS SILDANI KRISTI DAMOPOLII, S.IP', 'Perencana / Penyusun Program Anggaran dan Pelaporan (JP)', '198308122015031002', 'III/b', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(44, 48, 2, 'MUHAMMAD DANI SUARMAN, SE', 'Analis Keuangan (JP)', '198806032015031004', 'III/b', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(45, 49, 2, 'EUIS HERWATI', 'Perawat / Pengelola Keperawatan (JP)', '197105311993032003', 'III/b', '29|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(46, 50, 2, 'ASEP SAEPULOH, Amd.Kep', 'Perawat Mahir (JF)', '198209292009021001', 'III/b', '14|0|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(47, 51, 2, 'LUKI SUMARTO, SKM', 'Epidemiolog Kesehatan Ahli Pertama (JF)', '198607052008011004', 'III/b', '15|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(48, 52, 2, 'ARSY NESSYA PRAMUDYAWANTI, SKM', 'Entomolog Kesehatan Ahli Pertama (JF)', '198811112010122004', 'III/b', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(49, 53, 2, 'YENNI RISSA', 'Epidemiolog Kesehatan / Pengelola Pengamatan Penyakit dan Imunisasi (JP)', '198601162010012004', 'III/b', '13|1|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(50, 54, 2, 'dr. NADINDRA MADHYASTHA', 'Dokter (JP)', '198609102020122003', 'III/b', '2|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(51, 55, 2, 'YUSEP NUROHMAN, SKM', 'Perawat / Pengelola Keperawatan (JP)', '198310152008121002', 'III/b', '14|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(52, 56, 2, 'DIAN INDRIASARI, SKM', 'Entomolog Kesehatan Ahli Pertama (JF)', '197907072008122001', 'III/b', '14|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(53, 57, 2, 'VHANIE VHALENTINE WIM BENY, A.Md', 'Bendahara (JP)', '198711032009122001', 'III/b', '13|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(54, 58, 2, 'MUHAMMAD NURDIN, SKM', 'Pembimbing Kesehatan Kerja / Analis Kesehatan Kerja (JP)', '198205092006041003', 'III/a', '16|10|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(55, 59, 2, 'HILDA FUZIDA ASRI, S.A.B', 'Pranata Keuangan APBN Mahir (JF)', '199005102010122001', 'III/a', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(56, 60, 2, 'PUTRI GARNADITHIA, S.A.P', 'Arsiparis Ahli Pertama (JF)', '198704072010122002', 'III/a', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(57, 61, 2, 'AWALUDIN', 'Perawat Mahir (JF)', '198010292010121002', 'III/a', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(58, 62, 2, 'ANIS YUSLICHAH', 'Perawat Mahir (JF)', '198601262010122001', 'III/a', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(59, 63, 2, 'TRIADI ANUGERAH, SKM', 'Epidemiolog Kesehatan Ahli Pertama (JF)', '198910072010121005', 'III/a', '12|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(60, 64, 2, 'MARINA MAKBUL, SKM', 'Entomolog Kesehatan Ahli Pertama (JF)', '198206162012122001', 'III/a', '10|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(61, 65, 2, 'NUNI NURBAYANI', 'Epidemiolog Kesehatan / Pengelola Pengamatan Penyakit dan Imunisasi (JP)', '198801012012122002', 'III/a', '10|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(62, 66, 2, 'SULAEMAN', 'Sanitarian / Pengelola Penyehatan Lingkungan (JP)', '198804092012121001', 'III/a', '10|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(63, 67, 2, 'LISDA DARYATI', 'Sanitarian / Pengelola Penyehatan Lingkungan (JP)', '199005252012122001', 'III/a', '10|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(64, 68, 2, 'WIDYANTI OKTAVIANI, S.E', 'Perencana Ahli Pertama (JP)', '199410012022032001', 'III/a', '0|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(65, 69, 2, 'RYAN JULIANSYAH', 'Entomolog Kesehatan Mahir (JF)', '198907252015031002', 'III/a', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(66, 70, 2, 'INDAH DINAR APRIYANTI', 'Perawat Terampil (JF)', '198204152014022001', 'II/d', '9|0|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(67, 71, 2, 'EVA LISMAWATI', 'Perawat Terampil (JF)', '199111292014022003', 'II/d', '9|0|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(68, 72, 2, 'DEWI PATMAWATI', 'Entomolog Kesehatan Terampil (JF)', '199110112015032002', 'II/d', '7|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(69, 73, 2, 'TATANG SUPRIATNA', 'Arsiparis / Pranata Kearsipan (JP)', '197603032008121002', 'II/d', '14|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(70, 74, 2, 'ARIF RAHMAWAN, A.Md.A.K.', 'Pranata Laboratorium Kesehatan (JP)', '199206152020121007', 'II/c', '2|2|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(71, 75, 2, 'HANDY DINAR ASTIANSYAH, A.Md.Farm', 'Asisten Apoteker Terampil (JP)', '199002192022031002', 'II/c', '0|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(72, 76, 2, 'AYU RETNO SETYOWATI, AMKL', 'Epidemiolog Kesehatan Terampil (JP)', '199203042022032006', 'II/c', '0|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(73, 77, 2, 'MOHAMAD DAHLAN FAZHRY, A.md', 'Pranata Komputer Terampil (JP)', '199404082022031001', 'II/c', '0|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(74, 78, 2, 'ANGGIA MAUDY PRATIWI, A.Md', 'Pranata Keuangan APBN Terampil (JP)', '199408212022032002', 'II/c', '0|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(75, 79, 2, 'AKMAL FIRMANSYAH PUTRA, Amd.KL', 'Epidemiolog Kesehatan Terampil (JP)', '199608112022031001', 'II/c', '0|11|0', NULL, 2, 2, '2023-03-11 17:35:00', '2023-03-11 17:35:00');

-- --------------------------------------------------------

--
-- Table structure for table `infografis_kapal`
--

CREATE TABLE `infografis_kapal` (
  `id_infografis_kapal` int(11) NOT NULL,
  `unit_kerja` varchar(128) NOT NULL,
  `kapal_datang_negeri` int(11) NOT NULL,
  `kapal_berangkat_negeri` int(11) NOT NULL,
  `abk_datang_negeri` int(11) NOT NULL,
  `abk_berangkat_negeri` int(11) NOT NULL,
  `penumpang_datang_negeri` int(11) NOT NULL,
  `penumpang_berangkat_negeri` int(11) NOT NULL,
  `kapal_datang_luar` int(11) NOT NULL,
  `kapal_berangkat_luar` int(11) NOT NULL,
  `abk_datang_luar` int(11) NOT NULL,
  `abk_berangkat_luar` int(11) NOT NULL,
  `penumpang_datang_luar` int(11) NOT NULL,
  `penumpang_berangkat_luar` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_pnbp` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `infografis_kapal`
--

INSERT INTO `infografis_kapal` (`id_infografis_kapal`, `unit_kerja`, `kapal_datang_negeri`, `kapal_berangkat_negeri`, `abk_datang_negeri`, `abk_berangkat_negeri`, `penumpang_datang_negeri`, `penumpang_berangkat_negeri`, `kapal_datang_luar`, `kapal_berangkat_luar`, `abk_datang_luar`, `abk_berangkat_luar`, `penumpang_datang_luar`, `penumpang_berangkat_luar`, `status`, `created_by`, `updated_by`, `updated_pnbp`, `created_at`, `updated_at`) VALUES
(3, 'BANDARA HUSEN', 7, 2, 50, 14, 500, 1000, 7, 1, 49, 7, 700, 1000, 0, 6, 6, 0, '2023-02-10 15:36:46', '2023-02-10 15:36:46'),
(4, 'KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG', 12, 3, 56, 21, 720, 3500, 10, 2, 70, 14, 940, 2000, 0, 6, 6, 0, '2023-02-10 15:45:06', '2023-02-10 15:45:06'),
(10, 'BANDARA HUSEN', 9, 9, 99, 99, 999, 999, 9, 9, 99, 99, 999, 999, 0, 7, 7, 0, '2023-02-14 09:31:51', '2023-02-14 09:31:51'),
(11, 'BANDARA HUSEN', 10, 10, 10, 10, 10, 101, 10, 10, 10, 10, 10, 10, 0, 7, 7, 0, '2023-02-17 08:44:43', '2023-02-17 08:44:43'),
(12, 'KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG', 2, 22, 2, 22, 2, 22, 2, 22, 2, 22, 2, 22, 0, 7, 6, 0, '2023-02-20 11:39:44', '2023-02-20 11:43:12');

-- --------------------------------------------------------

--
-- Table structure for table `infografis_pesawat`
--

CREATE TABLE `infografis_pesawat` (
  `id_infografis_pesawat` int(11) NOT NULL,
  `unit_kerja` varchar(128) NOT NULL,
  `pesawat_datang_negeri` int(11) NOT NULL,
  `pesawat_berangkat_negeri` int(11) NOT NULL,
  `crew_datang_negeri` int(11) NOT NULL,
  `crew_berangkat_negeri` int(11) NOT NULL,
  `penumpang_datang_negeri` int(11) NOT NULL,
  `penumpang_berangkat_negeri` int(11) NOT NULL,
  `pesawat_datang_luar` int(11) NOT NULL,
  `pesawat_berangkat_luar` int(11) NOT NULL,
  `crew_datang_luar` int(11) NOT NULL,
  `crew_berangkat_luar` int(11) NOT NULL,
  `penumpang_datang_luar` int(11) NOT NULL,
  `penumpang_berangkat_luar` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_pnbp` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `infografis_pesawat`
--

INSERT INTO `infografis_pesawat` (`id_infografis_pesawat`, `unit_kerja`, `pesawat_datang_negeri`, `pesawat_berangkat_negeri`, `crew_datang_negeri`, `crew_berangkat_negeri`, `penumpang_datang_negeri`, `penumpang_berangkat_negeri`, `pesawat_datang_luar`, `pesawat_berangkat_luar`, `crew_datang_luar`, `crew_berangkat_luar`, `penumpang_datang_luar`, `penumpang_berangkat_luar`, `status`, `created_by`, `updated_by`, `updated_pnbp`, `created_at`, `updated_at`) VALUES
(1, 'KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG', 0, 1, 0, 15, 0, 150, 0, 5, 0, 100, 0, 2000, 0, 2, 2, 0, '2023-02-10 11:40:14', '2023-02-10 11:40:14'),
(3, 'KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG', 4, 5, 90, 10, 1900, 500, 5, 5, 10, 10, 2000, 2000, 0, 6, 6, 0, '2023-02-13 09:37:21', '2023-02-13 09:37:21'),
(4, 'BANDARA HUSEN', 10, 10, 45, 35, 203, 350, 10, 10, 50, 13, 100, 240, 0, 7, 7, 0, '2023-02-14 11:10:04', '2023-02-14 11:10:04'),
(5, 'BANDARA HUSEN', 121, 41, 312, 12, 121, 413, 21, 121, 21, 31, 21, 12, 0, 7, 7, 0, '2023-02-17 08:45:13', '2023-02-17 08:45:13');

-- --------------------------------------------------------

--
-- Table structure for table `infografis_vaksin`
--

CREATE TABLE `infografis_vaksin` (
  `id_infografis_vaksin` int(11) NOT NULL,
  `unit_kerja` varchar(128) NOT NULL,
  `phqc` int(11) NOT NULL,
  `cop` int(11) NOT NULL,
  `sscec` int(11) NOT NULL,
  `sscc` int(11) NOT NULL,
  `p3k` int(11) NOT NULL,
  `icv` int(11) NOT NULL,
  `sklt` int(11) NOT NULL,
  `siaos` int(11) NOT NULL,
  `siaj` int(11) NOT NULL,
  `kir` int(11) NOT NULL,
  `vaksin_mm` int(11) NOT NULL,
  `vaksin_yf` int(11) NOT NULL,
  `vaksin_covid` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `updated_pnbp` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `infografis_vaksin`
--

INSERT INTO `infografis_vaksin` (`id_infografis_vaksin`, `unit_kerja`, `phqc`, `cop`, `sscec`, `sscc`, `p3k`, `icv`, `sklt`, `siaos`, `siaj`, `kir`, `vaksin_mm`, `vaksin_yf`, `vaksin_covid`, `status`, `created_by`, `updated_by`, `updated_pnbp`, `created_at`, `updated_at`) VALUES
(1, 'BANDARA HUSEN', 1, 1, 1, 1, 1, 1, 1, 1, 1, 5, 1, 1, 1, 0, 7, 7, 0, '2023-02-15 13:42:20', '2023-02-17 08:43:00'),
(2, 'BANDARA HUSEN', 7, 0, 5, 3, 8, 6, 3, 9, 6, 3, 7, 77, 99, 0, 7, 7, 0, '2023-02-17 08:45:36', '2023-02-17 08:48:23');

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id_jenis` int(11) NOT NULL,
  `kategori` enum('cuti','perizinan') NOT NULL,
  `keterangan` varchar(128) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role_access`
--

CREATE TABLE `role_access` (
  `id_role_access` int(11) NOT NULL,
  `role` varchar(15) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `role_access`
--

INSERT INTO `role_access` (`id_role_access`, `role`, `created_at`, `updated_at`) VALUES
(12863, 'Pegawai', '2023-02-07 04:15:01', '2023-02-07 04:15:01'),
(21313, 'Admin', '2023-02-07 04:15:01', '2023-02-07 04:15:01'),
(43251, 'PNBP', '2023-02-10 08:08:32', '2023-02-10 08:08:32'),
(54253, 'Verifikator', '2023-02-07 04:15:01', '2023-02-07 04:15:01');

-- --------------------------------------------------------

--
-- Table structure for table `skema_titimangsa`
--

CREATE TABLE `skema_titimangsa` (
  `id_skema_titimangsa` int(11) NOT NULL,
  `id_unit_kerja` int(11) NOT NULL,
  `jenis_surat` varchar(50) NOT NULL,
  `kepegawaian` int(11) NOT NULL,
  `pejabat` int(11) NOT NULL,
  `atasan` int(11) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `skema_titimangsa`
--

INSERT INTO `skema_titimangsa` (`id_skema_titimangsa`, `id_unit_kerja`, `jenis_surat`, `kepegawaian`, `pejabat`, `atasan`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(4, 2, 'cuti', 5, 2, 4, 2, 2, '2023-03-01 09:13:02', '2023-03-01 09:13:02'),
(5, 2, 'surat_sakti', 5, 0, 4, 2, 2, '2023-03-06 15:18:07', '2023-03-06 15:18:07');

-- --------------------------------------------------------

--
-- Table structure for table `unit_kerja`
--

CREATE TABLE `unit_kerja` (
  `id_unit_kerja` int(11) NOT NULL,
  `unit_kerja` varchar(128) NOT NULL,
  `lokasi` varchar(25) NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `unit_kerja`
--

INSERT INTO `unit_kerja` (`id_unit_kerja`, `unit_kerja`, `lokasi`, `created_by`, `updated_by`, `created_at`, `updated_at`) VALUES
(2, 'KANTOR KESEHATAN PELABUHAN KELAS II BANDUNG', 'Bandung', 2, 2, '2023-02-07 14:44:50', '2023-02-07 14:44:50'),
(3, 'BANDARA HUSEN', 'Bandung', 6, 6, '2023-02-10 13:38:15', '2023-02-10 13:38:15');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `id_role_access` int(11) NOT NULL,
  `nama` varchar(50) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `id_role_access`, `nama`, `username`, `password`, `created_at`, `updated_at`) VALUES
(2, 21313, 'Hadian Zainal Muttaqin', 'hadianzm', '$2y$10$7JmH.Z3E51FUsLcLk36J3uDHD0K2g1RHbTnhBldmKPNhuUV4Awty.', '2023-02-07 12:57:57', '2023-02-07 12:57:57'),
(6, 21313, 'drg. RESI ARISANDI, MM, MH', 'resiarisandi', '$2y$10$p2Au3JWziMXorf.qrnGRVezRBLsybZJjTl7NyJLQSWz5QPoVJJ/R.', '2023-02-09 14:56:21', '2023-02-09 14:56:21'),
(7, 12863, 'Contoh', 'contohhh', '$2y$10$EfWcDilPC71sjAswENkCiuwxSwCtO5LUbMey8BjTWWYC8eGhqqhx2', '2023-02-13 14:21:20', '2023-02-13 14:21:20'),
(8, 12863, 'Wartoni, SKM, MPH,', 'wartoni1', '$2y$10$HKP4213A1mIEnQdie02sGOo5yqJN3/rt84qfIDcwE2mGRfrlJdNn2', '2023-02-21 14:32:47', '2023-02-21 14:32:47'),
(9, 12863, 'Nuning Yayuk Wulandari', 'nuningyw', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-02-28 15:29:51', '2023-02-28 15:29:51'),
(10, 12863, 'YANI DWIYULI SETIANI, SKM, MKM', '197107301993032001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(11, 12863, 'dr. AMRIYAH SUCI NURANI', '197605202005012003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(12, 12863, 'dr. ELLY FARIDA', '197407022006042013', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(13, 12863, 'FITRI MAYAWATI, SKM', '197111191998032001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(14, 12863, 'dr. MEDI NURSASIH', '196605072009121001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(15, 12863, 'H. EDDY HARIANTO, SKM, M.Epid', '198006302006041003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(16, 12863, 'AGUS SUTOMO, SKM', '196808181992031002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(17, 12863, 'BOYKE TANAKA, SKM', '196610121988031005', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(18, 12863, 'TUKITNO, SKM', '196903081991031005', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(19, 12863, 'MULDIE, SKM', '197209051997031004', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(20, 12863, 'SUBHAN ZAENI, SAP', '196810171992031003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(21, 12863, 'dr. NANANG LIONCU', '198103152010121003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(22, 12863, 'YUSTIKAWATI, SKM, MKM', '197310272008012010', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(23, 12863, 'LIANA RICA MON VIA, SKM', '198410072008012012', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(24, 12863, 'TEGUH DHIKA ROHKUSWARA, SKM, M.Epid', '198706292008121001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(25, 12863, 'RIFI ADI SUCIPTO, SKM, MKM', '198509082010121002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(26, 12863, 'NURUL AFIFA, S.Si.T., M.Keb., MH.Kes.', '197712202008012012', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(27, 12863, 'BENEDECTUS BAYU SABDO KUSUMO, SKM, MKM', '197609282005011001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(28, 12863, 'MARIATI SINAGA', '196806261997032002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(29, 12863, 'MUGA MULYA, SAP', '197212251997031001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(30, 12863, 'J U H A N A', '197306081995031002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(31, 12863, 'YENI SURYAMAH, SKM, M.Epid', '197406142009022001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(32, 12863, 'PUTU DESY SUSANTHI, SKM', '198412212009022001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(33, 12863, 'INU WISNU', '196806141999031001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(34, 12863, 'YANI SALAM, S.Si', '197802202006042003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(35, 12863, 'dr. ARDINA CAHYA WIYANTI', '197308052014122002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(36, 12863, 'dr. WIDY ASTUTI', '198205212014122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(37, 12863, 'DUDY AFFIANDY, SKM., M.Si', '197905202006041009', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(38, 12863, 'RAHMAWATI JATI MURWANI', '198007102003122002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(39, 12863, 'SIRA ELKO, S.K.M', '198304062008121003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(40, 12863, 'KEKE RISKAWATI, SKM', '199106282015032004', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(41, 12863, 'MARLIAH SANTI HR, SKM', '197508232002122003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(42, 12863, 'DEDEH SUPRIATIN, AMK', '197708132001122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(43, 12863, 'KIKI ATANTIO BAGIYADI', '198202272005011001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(44, 12863, 'MOH. IMANUDDIN SALAM, SKM', '198404172006041002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(45, 12863, 'AMANDA CHERKAYANI SEJATI, SKM, MPH', '199206092015032005', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(46, 12863, 'DETIANTI SRI IRAWATI, SE', '198503302015032002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(47, 12863, 'IGNATIUS SILDANI KRISTI DAMOPOLII, S.IP', '198308122015031002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(48, 12863, 'MUHAMMAD DANI SUARMAN, SE', '198806032015031004', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(49, 12863, 'EUIS HERWATI', '197105311993032003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(50, 12863, 'ASEP SAEPULOH, Amd.Kep', '198209292009021001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(51, 12863, 'LUKI SUMARTO, SKM', '198607052008011004', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(52, 12863, 'ARSY NESSYA PRAMUDYAWANTI, SKM', '198811112010122004', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(53, 12863, 'YENNI RISSA', '198601162010012004', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(54, 12863, 'dr. NADINDRA MADHYASTHA', '198609102020122003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(55, 12863, 'YUSEP NUROHMAN, SKM', '198310152008121002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(56, 12863, 'DIAN INDRIASARI, SKM', '197907072008122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(57, 12863, 'VHANIE VHALENTINE WIM BENY, A.Md', '198711032009122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(58, 12863, 'MUHAMMAD NURDIN, SKM', '198205092006041003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(59, 12863, 'HILDA FUZIDA ASRI, S.A.B', '199005102010122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(60, 12863, 'PUTRI GARNADITHIA, S.A.P', '198704072010122002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(61, 12863, 'AWALUDIN', '198010292010121002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(62, 12863, 'ANIS YUSLICHAH', '198601262010122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(63, 12863, 'TRIADI ANUGERAH, SKM', '198910072010121005', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(64, 12863, 'MARINA MAKBUL, SKM', '198206162012122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(65, 12863, 'NUNI NURBAYANI', '198801012012122002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(66, 12863, 'SULAEMAN', '198804092012121001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(67, 12863, 'LISDA DARYATI', '199005252012122001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(68, 12863, 'WIDYANTI OKTAVIANI, S.E', '199410012022032001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(69, 12863, 'RYAN JULIANSYAH', '198907252015031002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(70, 12863, 'INDAH DINAR APRIYANTI', '198204152014022001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(71, 12863, 'EVA LISMAWATI', '199111292014022003', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(72, 12863, 'DEWI PATMAWATI', '199110112015032002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(73, 12863, 'TATANG SUPRIATNA', '197603032008121002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(74, 12863, 'ARIF RAHMAWAN, A.Md.A.K.', '199206152020121007', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(75, 12863, 'HANDY DINAR ASTIANSYAH, A.Md.Farm', '199002192022031002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(76, 12863, 'AYU RETNO SETYOWATI, AMKL', '199203042022032006', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(77, 12863, 'MOHAMAD DAHLAN FAZHRY, A.md', '199404082022031001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(78, 12863, 'ANGGIA MAUDY PRATIWI, A.Md', '199408212022032002', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00'),
(79, 12863, 'AKMAL FIRMANSYAH PUTRA, Amd.KL', '199608112022031001', '$2y$10$/Vv6WI1gM1jd5UjHnDrCVuZFCD6aTjhjfwqXaJQaMK3XwQyqu9OmW', '2023-03-11 17:35:00', '2023-03-11 17:35:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cuti_data_cuti`
--
ALTER TABLE `cuti_data_cuti`
  ADD PRIMARY KEY (`id_cuti_data_cuti`);

--
-- Indexes for table `cuti_data_permohonan`
--
ALTER TABLE `cuti_data_permohonan`
  ADD PRIMARY KEY (`id_cuti_data_permohonan`);

--
-- Indexes for table `cuti_status`
--
ALTER TABLE `cuti_status`
  ADD PRIMARY KEY (`id_cuti_status`);

--
-- Indexes for table `data_pegawai`
--
ALTER TABLE `data_pegawai`
  ADD PRIMARY KEY (`id_data_pegawai`);

--
-- Indexes for table `infografis_kapal`
--
ALTER TABLE `infografis_kapal`
  ADD PRIMARY KEY (`id_infografis_kapal`);

--
-- Indexes for table `infografis_pesawat`
--
ALTER TABLE `infografis_pesawat`
  ADD PRIMARY KEY (`id_infografis_pesawat`);

--
-- Indexes for table `infografis_vaksin`
--
ALTER TABLE `infografis_vaksin`
  ADD PRIMARY KEY (`id_infografis_vaksin`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id_jenis`);

--
-- Indexes for table `role_access`
--
ALTER TABLE `role_access`
  ADD PRIMARY KEY (`id_role_access`);

--
-- Indexes for table `skema_titimangsa`
--
ALTER TABLE `skema_titimangsa`
  ADD PRIMARY KEY (`id_skema_titimangsa`);

--
-- Indexes for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  ADD PRIMARY KEY (`id_unit_kerja`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cuti_data_cuti`
--
ALTER TABLE `cuti_data_cuti`
  MODIFY `id_cuti_data_cuti` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `cuti_data_permohonan`
--
ALTER TABLE `cuti_data_permohonan`
  MODIFY `id_cuti_data_permohonan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `cuti_status`
--
ALTER TABLE `cuti_status`
  MODIFY `id_cuti_status` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `data_pegawai`
--
ALTER TABLE `data_pegawai`
  MODIFY `id_data_pegawai` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `infografis_kapal`
--
ALTER TABLE `infografis_kapal`
  MODIFY `id_infografis_kapal` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `infografis_pesawat`
--
ALTER TABLE `infografis_pesawat`
  MODIFY `id_infografis_pesawat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `infografis_vaksin`
--
ALTER TABLE `infografis_vaksin`
  MODIFY `id_infografis_vaksin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id_jenis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role_access`
--
ALTER TABLE `role_access`
  MODIFY `id_role_access` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54255;

--
-- AUTO_INCREMENT for table `skema_titimangsa`
--
ALTER TABLE `skema_titimangsa`
  MODIFY `id_skema_titimangsa` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `unit_kerja`
--
ALTER TABLE `unit_kerja`
  MODIFY `id_unit_kerja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

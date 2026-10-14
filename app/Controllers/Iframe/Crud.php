<?php

namespace App\Controllers\Iframe;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\RequestInterface;

class Crud extends BaseController
{
  public function setLink()
  {
    $id_iframe = $this->request->getVar("id_iframe");
    $segment = $this->request->getVar("segment");
    $title   = $this->request->getVar("title");
    $sidebar   = $this->request->getVar("sidebar");
    $link    = $this->request->getVar("link");

    if (empty($title) || empty($link)) {
      session()->setFlashdata('error', 'Title dan link wajib diisi.');
      return redirect()->to('iframe/setLink');
    }

    if (!$this->isValidUrl($link)) {
      session()->setFlashdata('error', 'URL tidak valid.');
      return redirect()->to('iframe/setLink');
    }

    // if (!$this->isUrlReachable($link)) {
    //   session()->setFlashdata('error', 'Link tidak dapat dijangkau.');
    //   return redirect()->to('iframe/setLink');
    // }

    if (!$this->canBeEmbeddedInIframe($link)) {
      session()->setFlashdata('error', 'Link tidak dapat ditampilkan di dalam iframe.');
      return redirect()->to('iframe/setLink');
    }

    if ($id_iframe == "") {
      $existingLink = $this->iframeModel->where('link', $link)->first();
      if ($existingLink) {
        session()->setFlashdata('error', 'Link ini sudah pernah ditambahkan sebelumnya.');
        return redirect()->to('iframe/setLink');
      }
    }

    if (empty($segment)) {
      $segment = generateRandomText();
    }

    $count = 1;
    while ($this->getSegment($segment)) {
      $segment = generateRandomText();

      if ($count == 5) {
        session()->setFlashdata('error', 'Link gagal diproses, silahkan coba kembali!');
        return redirect()->to('iframe/setLink');
      }
      $count++;
    }

    $data = [];

    if ($id_iframe != "") {
      $data += [
        'id_iframe' => $id_iframe
      ];
    }

    $data += [
      'segment' => $segment,
      'title'   => $title,
      'sidebar' => $sidebar,
      'link'    => $link,
    ];

    if ($this->iframeModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil disimpan!');
    } else {
      session()->setFlashdata('error', 'Data gagal disimpan!');
    }
    return redirect()->to('iframe/setLink');
  }

  public function getSegment($segment)
  {
    $cek = $this->iframeModel->where('segment', $segment)->first();
    return !empty($cek);
  }

  public function isValidUrl($url)
  {
    return filter_var($url, FILTER_VALIDATE_URL);
  }

  public function isUrlReachable($url)
  {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_exec($ch);

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $httpCode >= 200 && $httpCode < 400;
  }

  public function canBeEmbeddedInIframe($url)
  {
    $headers = get_headers($url, 1);

    if (!$headers) return true;

    if (isset($headers['X-Frame-Options'])) {
      return false;
    }

    if (isset($headers['Content-Security-Policy'])) {
      $csp = $headers['Content-Security-Policy'];
      if (is_array($csp)) $csp = implode(';', $csp);
      if (stripos($csp, 'frame-ancestors') !== false && stripos($csp, "'none'") !== false) {
        return false;
      }
    }

    return true;
  }
  public function dlt_iframe($id)
  {
    if ($this->iframeModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
  }
}

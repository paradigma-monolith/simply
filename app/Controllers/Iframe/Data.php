<?php

namespace App\Controllers\Iframe;

use App\Controllers\BaseController;

class Data extends BaseController
{
  public function iframe($segment)
  {
    $data['activeMenu'] = "";

    $iframe = $this->iframeModel->where('segment', $segment)->first();
    $data['title'] = "Konten Tersematkan | " . $iframe['title'];

    $data['segmentNow'] = $segment;

    $data['iframe'] = $iframe;

    return view('iframe/index', $data);
  }
  public function setLink()
  {
    $data['title'] = "Sematkan Konten | Pengelolaan Link";
    $data['activeMenu'] = "iframeSetLink";

    $data['getData'] = $this->iframeModel->orderBy('sidebar', 'asc')->findAll();

    return view('iframe/set_link', $data);
  }
  public function menu()
  {
    if ($this->request->isAJAX()) {
      if (session_status() === PHP_SESSION_ACTIVE) {
        session_write_close();
      }

      return $this->response->setJSON([
        'data' => $this->iframeModel->select('segment, sidebar')->orderBy('sidebar', 'asc')->findAll()
      ]);
    }
  }
}

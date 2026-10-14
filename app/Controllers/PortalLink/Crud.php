<?php

namespace App\Controllers\PortalLink;

use App\Controllers\BaseController;
use App\Models\PortalLinkModel;

class Crud extends BaseController
{
  public function setLink()
  {
    $id_portal = $this->request->getVar("id_portal");
    $title     = $this->request->getVar("title");
    $link      = $this->request->getVar("link");

    if (empty($title) || empty($link)) {
      session()->setFlashdata('error', 'Title dan link wajib diisi.');
      return redirect()->to('portal_link/setLink');
    }

    if (!filter_var($link, FILTER_VALIDATE_URL)) {
      session()->setFlashdata('error', 'URL tidak valid.');
      return redirect()->to('portal_link/setLink');
    }

    $portalLinkModel = new PortalLinkModel();

    $data = [
      'title' => $title,
      'link'  => $link,
    ];

    if ($id_portal != "") {
      $data['id_portal'] = $id_portal;
    }

    if ($portalLinkModel->save($data)) {
      session()->setFlashdata('pesan', 'Data berhasil disimpan!');
    } else {
      session()->setFlashdata('error', 'Data gagal disimpan!');
    }
    
    return redirect()->to('portal_link/setLink');
  }

  public function delete($id)
  {
    $portalLinkModel = new PortalLinkModel();
    if ($portalLinkModel->delete($id)) {
      session()->setFlashdata('pesan', 'Data berhasil dihapus.');
    } else {
      session()->setFlashdata('error', 'Data gagal dihapus.');
    }
  }
}

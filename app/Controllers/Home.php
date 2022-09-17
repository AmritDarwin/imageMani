<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('welcome_message');
    }

    public function image()
    {
        if ($this->request->getMethod() == 'post') {
            $rules = [
                'name'  => 'required',
                'email' => 'required|min_length[10]',
                'mobile' => 'required|matches[password]',
                'image' => 'uploaded[image]|max_size[avatar,1024]',
            ];
            if (!$this->validation->setRules($rules)) return $this->response->setJSON(['type' => 'error', 'message' => $this->validation->getErrors()]);
            list($width, $height) = getimagesize(FCPATH . 'assets/bg.jpg');
            $im = imagecreatetruecolor($width, $height) or die("cannot generate img");
            // imagefilledrectangle($im, 0, 0, 299, 299, imagecolorallocate($im, 255, 255, 255));
            $bg = imagecreatefromjpeg(FCPATH . 'assets/bg.jpg');

            imagecopyresampled($im, $bg, 0, 0, 0, 0, $height, $width, $height, $width);
            $file = $this->request->getFile('image');
            // // $file->move(FCPATH. 'assets');
            $user = imagecreatefromjpeg($file->getRealPath());
            $filename = $file->getRandomName();

            // for text

            $img = imagecreate(700, 500);
            $white = imagecolorallocate($img, 255, 255, 255);

            //transparent
            imagealphablending($img, false);
            $transparency = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $transparency);
            imagesavealpha($img, true);
            // end transparent
            imagefilledrectangle($img, 0, 0, 0, 0, imagecolorallocate($img, 0, 0, 0));

            $text = $this->request->getPost('name');

            $bbox = imagettfbbox(20, 0, FCPATH . 'fonts/OpenSans-Regular.ttf', $text) or die('Error in imagettfbbox function');

            $txt_w = $bbox[4] - $bbox[0];
            $txt_h = $bbox[3] - $bbox[1];
            $scale = 700 / $txt_w;

            $fontSize = 20 * $scale;

            imagefttext($img, $fontSize, 0, $bbox[0] + 10, $bbox[1] + 100, imagecolorallocate($img, 0, 0, 0), FCPATH . 'fonts/OpenSans-Regular.ttf', $text);
            header('Content-Type: image/jpeg');

            // end text
            // add text
            imagecopyresampled($im, $img, 10, 10, 0, 0, 300, 100, $height, $width);

            // add user img
            imagecopyresampled($im, $user, 100, 100, 0, 0, $height * 0.50, $width * 0.50, $height, $width);
            imagejpeg($im, FCPATH . 'assets/' . $filename);
            imagedestroy($im);
            return $this->response->setJSON(['img' => $filename]);
            // $path = $this->image->withFile(FCPATH . 'assets/bg.jpg')->fit(100, 100, 'center')->save(FCPATH . 'assets/custom.jpg');
            // print_r($path);
            // die;
        }
    }
}

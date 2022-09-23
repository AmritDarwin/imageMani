<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function __construct()
    {
        $this->model = new \App\Models\Home();
    }

    public function index()
    {
        return view('welcome_message');
    }

    public function image()
    {
        if ($this->request->getMethod() == 'post') {

            $template = $this->model->getTemplate('independence');
            $userImageCoordinates = json_decode($template['image_coordinates']);
            $userNameCoordinates = json_decode($template['name_coordinates']);
            // $rules = [
            //     'name'  => 'required',
            //     'email' => 'required|min_length[10]',
            //     'mobile' => 'required|matches[password]',
            //     'image' => 'uploaded[image]|max_size[avatar,1024]',
            // ];

            // if (!$this->validation->setRules($rules)) return $this->response->setJSON(['type' => 'error', 'message' => $this->validation->getErrors()]);
            $user_image_coordinates = [$userImageCoordinates->tl->x, $userImageCoordinates->tl->y];
            // var_dump($user_image_coordinates);die;

            $user_width = (int) $userImageCoordinates->tr->x - (int) $userImageCoordinates->tl->x;
            $user_height = (int) $userImageCoordinates->tr->x - (int) $userImageCoordinates->tl->x;
            $text_image_height = (int) $userNameCoordinates->bl->y - $userNameCoordinates->tl->y;

            $text = $this->request->getPost('name');
            // base64 to img
            $img = $this->request->getPost('image');
            $img = str_replace('data:image/png;base64,', '', $img);
            $img = str_replace(' ', '+', $img);
            $data = base64_decode($img);
            $filename = uniqid() . '.png';
            $file = FCPATH . "assets/" . $filename;
            file_put_contents($file, $data);
            // base64 to img end
            // check background dimension
            list($width, $height) = getimagesize(FCPATH . 'assets/independence.png');
            // create image canvas of dimension
            $im = imagecreatetruecolor($width, $height) or die("cannot generate img");
            // create gd image of background 
            $bg = imagecreatefrompng(FCPATH . 'assets/independence.png');
            // create uploaded gd image
            $user = imagecreatefrompng(FCPATH . 'assets/' . $filename);
            // resize user image
            $user = $this->imageResize($filename, $user, $user_width, $user_height);
            // add user img to canvas
            imagecopyresampled($im, $user, $user_image_coordinates[0], $user_image_coordinates[1], 0, 0, $width, $height, $width, $height);
            // add gd background image to canvas
            imagecopyresampled($im, $bg, 0, 0, 0, 0, $width, $height, $width, $height);
            // create new gb image for text
            $text_im = $this->addText($text, $text_image_height, $width);
            // add gb image with text on bg image
            imagecopyresampled($im, $text_im, 0, $height - $text_image_height, 0, 0, $width, $height, $width, $height);
            // save image to destination
            imagejpeg($im, FCPATH . 'assets/' . $filename);
            imagedestroy($im);
            return $this->response->setJSON(['img' => $filename]);
        }
    }

    function imageResize($filename, $image, $w, $h)
    {
        $oldw = imagesx($image);
        $oldh = imagesy($image);
        $temp = imagecreatetruecolor($w, $h);
        imagecopyresampled($temp, $image, 0, 0, 0, 0, $w, $h, $oldw, $oldh);
        imagepng($temp, FCPATH . 'assets/' . $filename);
        // imagedestroy($temp);
        return $temp;
    }

    function addText($text, $h, $w)
    {
        // create gd image
        $text_im = imagecreatetruecolor($w, $h);
        //for transparent background
        imagealphablending($text_im, false);
        imagesavealpha($text_im, true);
        // add transprent color to gd image
        $col = imagecolorallocatealpha($text_im, 255, 255, 255, 127);
        // set transprant color to image 
        imagefill($text_im, 0, 0, $col);
        // text info
        $white = imagecolorallocate($text_im, 255, 255, 255); //for font color
        $black = imagecolorallocate($text_im, 0, 0, 0); //for font color
        $grey = imagecolorallocate($text_im, 128, 128, 128); // for shadow color
        $font = FCPATH . "fonts/OpenSans-Regular.ttf"; //font path
        $fontsize = 20; // size of your font
        $x = 100; // x- position of your text
        $y = 50; // y- position of your text
        $angle = 0;
        // Get Bounding Box Size
        $text_box = imagettfbbox($fontsize, $angle, $font, $text);
        // Get your Text Width and Height
        $text_width = $text_box[2] - $text_box[0];
        $text_height = $text_box[7] - $text_box[1];
        // Calculate coordinates of the text
        $x = round(($w / 2) - ($text_width / 2));
        $y = round(($h / 2) - ($text_height / 2));
        // Add some shadow to the text
        imagettftext($text_im, $fontsize, $angle, $x + 1, $y + 1, $black, $font, $text);
        // Add the text
        imagettftext($text_im, $fontsize, $angle, $x, $y, $white, $font, $text);
        imagepng($text_im, FCPATH . '/text.png');
        return $text_im;
    }

    public function admin()
    {
        return view('admin/index');
    }

    public function addTemplate()
    {
        if ($this->request->getMethod() == 'post') {
            $name               = $this->request->getPost('name');
            $background         = $this->request->getFile('background');
            $image_coordinates  = $this->request->getPost('image_coordinates');
            $name_coordinates   = $this->request->getPost('name_coordinates');
            $email_coordinates  = $this->request->getPost('email_coordinates');
            $mobile_coordinates = $this->request->getPost('mobile_coordinates');
            $text_color         = $this->request->getPost('text_color');

            if (!$background->hasMoved()) {
                $filename = $background->getName();
                $background->move(FCPATH . 'assets/', $filename);

                $data = [
                    'name'              => $name,
                    'background'        => $filename,
                    'image_coordinates' => $image_coordinates,
                    'name_coordinates'  => $name_coordinates,
                    'email_coordinates' => $email_coordinates,
                    'mobile_coordinates' => $mobile_coordinates,
                    'text_color'        => $text_color,
                    'status'            => 1,
                    'created_at'        => date('Y-m-d H:i:s')
                ];

                if ($this->model->addTemplate($data))
                    echo 1;
                else
                    echo 0;
            }
        }
    }

    public function getTemplate($name)
    {
        $template = $this->model->getTemplate($name);
        if (empty($template)) return;
    }
}

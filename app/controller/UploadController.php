<?php
namespace app\controller;
use Intervention\Image\ImageManagerStatic as Image;
use Random\RandomException;
use support\exception\BusinessException;
use support\Request;
use support\Response;

class UploadController
{
    /**
     * 上传图片
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function image(Request $request): Response
    {
        switch ($request->get('type',1)) {
            case 1:
                $imgPath = '/upload/img/' . date('Ymd');
                break;
            case 2:
                $imgPath = '/upload/img/daily/' . date('Ymd');
                break;
        }
        $data = $this->base($request, $imgPath);
        $realpath = $data['realpath'];
        try {
            // 临时增加内存限制，避免内存不足问题
            ini_set('memory_limit', '512M');
            $img = Image::make($realpath)->orientate();
            $max_height = 1170;
            $max_width = 1170;
            $width = $img->width();
            $height = $img->height();
            $ratio = 1;
            if ($height > $max_height || $width > $max_width) {
                $ratio = $width > $height ? $max_width / $width : $max_height / $height;
            }
            $img->resize($width*$ratio, $height*$ratio)->save($realpath);
        } catch (\Throwable $e) {
            unlink($realpath);
            return json( [
                'code'  => 500,
                'msg'  => '处理图片发生错误'
            ]);
        }
        return json( [
            'code'  => 0,
            'msg'  => '上传成功',
            'data' => [
                'url' => $data['url'],
                'name' => $data['name'],
                'size' => $data['size'],
            ]
        ]);
    }

    /**
     * 获取上传数据
     * @param Request $request
     * @param $relative_dir
     * @return array
     * @throws BusinessException|RandomException
     */
    protected function base(Request $request, $relative_dir): array
    {
        $relative_dir = ltrim($relative_dir, '\\/');
        $file = current($request->file());
        if (!$file || !$file->isValid()) {
            throw new BusinessException('未找到上传文件', 400);
        }

        $admin_public_path = rtrim(config('plugin.admin.app.public_path', ''), '\\/');
        $base_dir = $admin_public_path ? $admin_public_path . DIRECTORY_SEPARATOR : base_path() . '/plugin/admin/public/';
        $full_dir = $base_dir . $relative_dir;
        if (!is_dir($full_dir)) {
            mkdir($full_dir, 0777, true);
        }

        $ext = $file->getUploadExtension() ?: null;
        $mime_type = $file->getUploadMimeType();
        $file_name = $file->getUploadName();
        $file_size = $file->getSize();

        if (!$ext && $file_name === 'blob') {
            [$___image, $ext] = explode('/', $mime_type);
            unset($___image);
        }

        $ext = strtolower($ext);
        $ext_forbidden_map = ['php', 'php3', 'php5', 'css', 'js', 'html', 'htm', 'asp', 'jsp'];
        if (in_array($ext, $ext_forbidden_map)) {
            throw new BusinessException('不支持该格式的文件上传', 400);
        }

        $relative_path = $relative_dir . '/' . bin2hex(pack('Nn',time(), random_int(1, 65535))) . ".$ext";
        $full_path = $base_dir . $relative_path;
        $file->move($full_path);
        $image_with = $image_height = 0;
        if ($img_info = getimagesize($full_path)) {
            [$image_with, $image_height] = $img_info;
            $mime_type = $img_info['mime'];
        }
        return [
            'url'     => "/app/admin/$relative_path",
            'name'     => $file_name,
            'realpath' => $full_path,
            'size'     => $file_size,
            'mime_type' => $mime_type,
            'image_with' => $image_with,
            'image_height' => $image_height,
            'ext' => $ext,
        ];
    }
}
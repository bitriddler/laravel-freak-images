<?php namespace Kareem3d\FreakImages;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Kareem3d\Freak\Core\PackageController;
use Kareem3d\Images\Image;
use Kareem3d\Images\ImageFacade;

class FreakImagesPackageController extends PackageController {

    /**
     * @var
     */
    protected $images;

    /**
     * @param Image $images
     */
    public function __construct(Image $images)
    {
        $this->images = $images;
    }

    /**
     *
     */
    public function getAll()
    {
        $array = array();

        foreach($this->data->getModel()->getAllImages() as $image)
        {
            $one['id']   = $image->id;
            $one['type'] = $image->type;
            $one['url']  = $image->getSmallest()->url;

            $array[] = $one;
        }

        return $array;
    }

    /**
     * @param $id
     */
    public function deleteOne($id)
    {
        $this->images->find($id)->delete();
    }

    /**
     * Upload all images
     */
    public function postUpload()
    {
        if($file = Input::file('file'))
        {
            $model = $this->data->getModel();

            $group    = $this->data->getOptionRequired('group');
            $fileName = $this->data->getOption('image_name', $file->getClientOriginalName());
            $type     = $this->data->getOption('image_type', '');

            $versions = ImageFacade::versions($group,$fileName, $file, false);

            $image = $this->images->create(array(

                'title' => $this->data->getOption('image_title', $model->title),
                'alt'   => $this->data->getOption('image_alt', $model->title)

            ))->add($versions);

            $model->addImage( $image, $type );
        }
    }

    /**
     * Show form view
     *
     * @return mixed
     */
    public function showForm()
    {
        return View::make('freak-images::form');
    }

    /**
     * Show detail view
     *
     * @return mixed
     */
    public function showDetail()
    {
        return View::make('freak-images::detail');
    }
}
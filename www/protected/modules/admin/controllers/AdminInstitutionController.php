<?php
class AdminInstitutionController extends VAdminController
{
	public $model = 'Institution';

    public $route = '/admin/adminInstitution';

    public function layoutsFilters() {
        return array(
            'status' => array(
                'type' => 'dropdownlist',
                'label' => 'Статус',
                'items' => Institution::statusTypes(),
                'empty' => 'Выбрать',
            ),
/*            
            'type' => array(
                'type' => 'dropdownlist',
                'label' => 'Тип',
                'items' => self::typesList (),
                'empty' => 'Выбрать',
            ),
*/
            'id' => array(
                'type' => 'text',
                'label' => 'id',
            ),
            'title' => array(
                'type' => 'text',
                'label' => 'Название',
            ),
        );
    }

    public function appendLayoutFilters($model, $cFilterForm) {
        if ($cFilterForm->model->status != "") {
            $model->byStatus($cFilterForm->model->status);
        }
        if ($cFilterForm->model->title != "") {
            $model->getDbCriteria()->addSearchCondition('title', $cFilterForm->model->title);
        }
        if ($cFilterForm->model->id != "") {
            $model->getDbCriteria()->mergeWith(array('condition' => $model->getTableAlias().'.id = '.$cFilterForm->model->id));
        }
        $model->orderDefault();
        return $model;
    }

    public function getListColumns() {
        return array(
            'title',
            array(
                'name'=>'devLink',
                'type' => 'raw',
                'value'=>'$data->title',
            ),
            /*
            array(
                'name'=>'sourceLink',
                'type' => 'raw',
                'value'=>'$data->getClientIssue() ? CHtml::link($data->clientSource, $data->getClientIssue()->getUrl()) : \'\'',
            ),
            */
            array(
                'name'=>'status',
                'type' => 'raw',
                'value'=>'$data->status',
            ),
            array(
                'class'=>'VAdminButtonWidget',
            ),
        );
    }

    public function getFormElements ($model)
    {
        return array(
            'title'=>array(
                'type'=> 'text',
            ),
            'fullTitle'=>array(
                'type'=> 'text',
            ),
            'type'=>array(
                'type'=> 'dropdownlist',
                'items'=> Institution::typeTypes(),
            ),
            'site'=>array(
                'type'=> 'text',
            ),
            'logo'=>array(
                'type'=> 'VHtmlPhotoWidget',
                'hint'=> 'Желательно 180px и более по ширине или высоте'
            ),
            'image'=>array(
                'type'=> 'VHtmlPhotoWidget',
                'hint'=> '965px и более по ширине, горизонтальное фото'
            ),
            '_phones' => array(
                'type' => 'EduPhonesWidget'
            ),
            '_emails' => array(
                'type' => 'EduEmailsWidget'
            ),
            '_addresses' => array(
                'type' => 'EduAddressesWidget'
            ),
            'announce'=>array(
                'type'=> 'textarea',
            ),
            'text'=>array(
                'type'=> 'VHtmlCkEditorWidget',
                'hint'=> 'Заголовок 2 становится закладкой, весь текст после него будет скрыт под закладку'
            ),
            /*
            '_customText'=>array(
                'type'=> 'EduCustomTextWidget',
            ),
            */
            'status'=>array(
                'type'=> 'dropdownlist',
                'items'=> Institution::statusTypes(),
//                'empty'=> 'Выбрать',
            ),
            'priority'=>array(
                'type'=> 'dropdownlist',
                'items'=> Institution::priorityTypes(),
//                'empty'=> 'Выбрать',
            ),
        );
    }

}
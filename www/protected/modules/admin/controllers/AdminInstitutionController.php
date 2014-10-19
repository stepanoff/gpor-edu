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

    public static function typesList ()
    {
        return array(
            '1' => 'Переданные в разработку',
            '2' => 'Не переданные в разработку',
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
                'type'=> 'textarea',
            ),
            'customText'=>array(
                'type'=> 'textarea',
            ),
            'status'=>array(
                'type'=> 'dropdownlist',
                'items'=> Institution::statusTypes(),
                'empty'=> 'Выбрать',
            ),
        );
    }

}
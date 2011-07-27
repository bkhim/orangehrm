<?php

class DetailedLeaveListConfigurationFactory extends ohrmListConfigurationFactory {
    
    protected static $listMode;

    public function init() {
        sfContext::getInstance()->getConfiguration()->loadHelpers('OrangeDate');
        
        $header1 = new ListHeader();
        $header3 = new ListHeader();
        $header4 = new ListHeader();
        $header5 = new ListHeader();
        $header6 = new ListHeader();
        $header7 = new ListHeader();

        $header1->populateFromArray(array(
            'name' => 'Date',
            'width' => '15%',
            'isSortable' => false,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getLeaveDate'),
        ));

        $header3->populateFromArray(array(
            'name' => 'Leave Type',
            'width' => '15%',
            'isSortable' => false,
            'elementType' => 'label',
            'elementProperty' => array(
                'getter' => array('getLeaveRequest', 'getLeaveType',  'getDescriptiveLeaveTypeName'),
                'hideIfCallback' => 'isNonWorkingDay',
             ),
        ));

        $header4->populateFromArray(array(
            'name' => 'Duration (hours)',
            'width' => '15%',
            'isSortable' => false,
            'elementType' => 'label',
            'elementProperty' => array('getter' => 'getLeaveLengthHours', 'hideIfCallback' => 'isNonWorkingDay'),
        ));

        $header5->populateFromArray(array(
            'name' => 'Status',
            'width' => '10%',
            'isSortable' => false,
            'elementType' => 'label',
            'elementProperty' => array(
                'getter' => array('getTextLeaveStatus'),
                'default' => 'Non Working Day',
            ),
        ));

        $header6->populateFromArray(array(
            'name' => 'Comments',
            'width' => '20%',
            'isSortable' => false,
            'elementType' => 'comment',
            'elementProperty' => array(
                'getter' => 'getLeaveComments',
                'idPattern' => 'hdnLeaveComment-{id}',
                'namePattern' => 'leaveComments[{id}]',
                'placeholderGetters' => array('id' => 'getLeaveId'),
                'hasHiddenField' => true,
                'hiddenFieldName' => 'leave[{id}]',
                'hiddenFieldId' => 'hdnLeave_{id}',
                'hiddenFieldValueGetter' => 'getLeaveId',
                'hideIfCallback' => 'isNonWorkingDay',
            ),
        ));

        $leaveRequestService = new LeaveRequestService();
        $header7->populateFromArray(array(
            'name' => 'Actions',
            'width' => '10%',
            'isSortable' => false,
            'isExportable' => false,
            'elementType' => 'selectSingle',
            'elementProperty' => array(
                'defaultOption' => array('label' => 'Select Action', 'value' => ''),
                'hideIfEmpty' => true,
                'hideIfCallback' => 'isNonWorkingDay',
                'options' => array($leaveRequestService, 'getLeaveActions', array(self::RECORD, self::$userId, self::$listMode)),
                'namePattern' => 'select_leave_action_{id}',
                'idPattern' => 'select_leave_action_{id}',
                'placeholderGetters' => array(
                    'id' => 'getLeaveId',
                ),
            ),
        ));

        $this->headers = array($header1, $header3, $header4, $header5, $header6, $header7);
    }
    
    public function getClassName() {
        return 'Leave';
    }

    public static function setListMode($listMode) {
        self::$listMode = $listMode;
    }
}
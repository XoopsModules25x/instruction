<?php

//if (!defined("XOOPS_ROOT_PATH")) {
//	die("XOOPS root path not defined");
//}

include_once $GLOBALS['xoops']->path('include/common.php');

class InstructionPage extends XoopsObject
{
    // constructor
    public function __construct()
    {
        //	$this->XoopsObject();
        $this->initVar('pageid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('pid', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('instrid', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('status', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('type', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('hometext', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('footnote', XOBJ_DTYPE_TXTAREA, '', false);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('keywords', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('description', XOBJ_DTYPE_TXTBOX, '', false, 255);
        $this->initVar('comments', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('datecreated', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('dateupdated', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 1, false, 1);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0, false, 1);
    }

    public function InstructionPage()
    {
        $this->__construct();
    }

    public function get_new_enreg()
    {
        $new_enreg = $GLOBALS['xoopsDB']->getInsertId();
        return $new_enreg;
    }

    // Получаем форму
    public function getForm($action = false, $instrid = 0)
    {
        // Если нет $action
        if (false === $action) {
            $action = xoops_getenv('REQUEST_URI');
        }

        // Подключаем формы
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');
        // Подключаем типы страниц
        $pagetypes = include $GLOBALS['xoops']->path('modules/instruction/include/pagetypes.inc.php');

        // Название формы
        $title = $this->isNew() ? sprintf(_AM_INSTRUCTION_FORMADDPAGE) : sprintf(_AM_INSTRUCTION_FORMEDITPAGE);

        // Форма
        $form = new XoopsThemeForm($title, 'instr_form_page', $action, 'post', true);
        // Название
        $form->addElement(new XoopsFormText(_AM_INSTRUCTION_TITLEC, 'title', 50, 255, $this->getVar('title')), true);

        // Родительская страница
        $inspageHandler = xoops_getModuleHandler('page', 'instruction');
        $criteria       = new CriteriaCompo();
        // ID инструкции в которой данная страница
        $instrid_page = $this->isNew() ? $instrid : $this->getVar('instrid');
        // Находим все страницы данной инструкции
        $criteria->add(new Criteria('instrid', $instrid_page, '='));
        // Если мы редактируем, то убрать текущую страницу из списка выбора родительской
        if (!$this->isNew()) {
            $criteria->add(new Criteria('pageid', $this->getVar('pageid'), '<>'));
        }
        $criteria->setSort('weight');
        $criteria->setOrder('ASC');
        $inspage_arr = $inspageHandler->getall($criteria);
        unset($criteria);
        // Подключаем трей
        include_once $GLOBALS['xoops']->path('class/tree.php');
        $mytree = new XoopsObjectTree($inspage_arr, 'pageid', 'pid');

        // $form->addElement(new XoopsFormLabel(_AM_INSTRUCTION_PPAGEC, $mytree->makeSelBox('pid', 'title', '--', $this->getVar('pid'), true)));
        $moduleDirName = basename(__DIR__);
        if (false !== ($moduleHelper = Xmf\Module\Helper::getHelper($moduleDirName))) {
        } else {
            $moduleHelper = Xmf\Module\Helper::getHelper('system');
        }
        $module = $moduleHelper->getModule();

        if (InstructionUtility::checkVerXoops($module, '2.5.9')) {
            $mytree_select = $mytree->makeSelectElement('pid', 'title', '--', $this->getVar('pid'), true, 0, '', _AM_INSTRUCTION_PPAGEC);
            $form->addElement($mytree_select);
        } else {
            $form->addElement(new XoopsFormLabel(_AM_INSTRUCTION_PPAGEC, $mytree->makeSelBox('pid', 'title', '--', $this->getVar('pid'), true)));
        }

        // Вес
        $form->addElement(new XoopsFormText(_AM_INSTRUCTION_WEIGHTC, 'weight', 5, 5, $this->getVar('weight')), true);
        // Основной текст
        $form->addElement(InstructionUtility::getWysiwygForm(_AM_INSTRUCTION_HOMETEXTC, 'hometext', $this->getVar('hometext', 'e')), true);
        // Сноска
        $form_footnote = new XoopsFormTextArea(_AM_INSTRUCTION_FOOTNOTEC, 'footnote', $this->getVar('footnote', 'e'));
        $form_footnote->setDescription(_AM_INSTRUCTION_FOOTNOTE_DSC);
        $form->addElement($form_footnote, false);
        unset($form_footnote);
        // Статус
        $form->addElement(new XoopsFormRadioYN(_AM_INSTRUCTION_ACTIVEC, 'status', $this->getVar('status')), false);
        // Тип страницы
        $form_type = new XoopsFormSelect(_AM_INSTR_PAGETYPEC, 'type', $this->getVar('type'));
        $form_type->setDescription(_AM_INSTR_PAGETYPEC_DESC);
        $form_type->addOptionArray($pagetypes);
        $form->addElement($form_type, false);
        // Мета-теги ключевых слов
        $form->addElement(new XoopsFormText(_AM_INSTRUCTION_METAKEYWORDSC, 'keywords', 50, 255, $this->getVar('keywords')), false);
        // Мета-теги описания
        $form->addElement(new XoopsFormText(_AM_INSTRUCTION_METADESCRIPTIONC, 'description', 50, 255, $this->getVar('description')), false);

        // Настройки
        $option_tray = new XoopsFormElementTray(_OPTIONS, '<br>');
        // HTML
        $html_checkbox = new XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml'));
        $html_checkbox->addOption(1, _AM_INSTR_DOHTML);
        $option_tray->addElement($html_checkbox);
        // Смайлы
        $smiley_checkbox = new XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley'));
        $smiley_checkbox->addOption(1, _AM_INSTR_DOSMILEY);
        $option_tray->addElement($smiley_checkbox);
        // ББ коды
        $xcode_checkbox = new XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode'));
        $xcode_checkbox->addOption(1, _AM_INSTR_DOXCODE);
        $option_tray->addElement($xcode_checkbox);
        //
        $br_checkbox = new XoopsFormCheckBox('', 'dobr', $this->getVar('dobr'));
        $br_checkbox->addOption(1, _AM_INSTR_DOAUTOWRAP);
        $option_tray->addElement($br_checkbox);
        //
        $form->addElement($option_tray);

        // Если мы редактируем страницу
        if (!$this->isNew()) {
            $form->addElement(new XoopsFormHidden('pageid', $this->getVar('pageid')));
        } else {
            $form->addElement(new XoopsFormHidden('pageid', 0));
        }
        // ID инструкции
        if ($instrid) {
            $form->addElement(new XoopsFormHidden('instrid', $instrid));
        } else {
            $form->addElement(new XoopsFormHidden('instrid', 0));
        }
        //
        $form->addElement(new XoopsFormHidden('op', 'savepage'));
        // Кнопка
        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $save_btn = new XoopsFormButton('', 'cancel', _AM_INSTR_SAVEFORM);
        $save_btn->setExtra('onclick="instrSavePage();"');
        $button_tray->addElement($save_btn);
        $form->addElement($button_tray);

        return $form;
    }

    //
    public function getInstrid()
    {
        // Возвращаем ID инструкции
        return $this->getVar('instrid');
    }
}

class InstructionPageHandler extends XoopsPersistableObjectHandler
{
    public function __construct($db)
    {
        parent::__construct($db, 'instruction_page', 'InstructionPage', 'pageid', 'title');
    }

    /**
     * Generate function for update user post
     *
     * @ Update user post count after send approve content
     * @ Update user post count after change status content
     * @ Update user post count after delete content
     */
    public function updateposts($uid, $status, $action)
    {
        //
        switch ($action) {
            // Добавление страницы
            case 'add':
                if ($uid && $status) {
                    $user          = new xoopsUser($uid);
                    $memberHandler = xoops_getHandler('member');
                    // Добавялем +1 к комментам
                    $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') + 1);
                }
                break;
            // Удаление страницы
            case 'delete':
                if ($uid && $status) {
                    $user          = new xoopsUser($uid);
                    $memberHandler = xoops_getHandler('member');
                    // Декримент комментов
                    //$user->setVar( 'posts', $user->getVar( 'posts' ) - 1 );
                    // Сохраняем
                    $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') - 1);
                }
                break;

            case 'status':
                if ($uid) {
                    $user          = new xoopsUser($uid);
                    $memberHandler = xoops_getHandler('member');
                    if ($status) {
                        $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') - 1);
                    } else {
                        $memberHandler->updateUserByField($user, 'posts', $user->getVar('posts') + 1);
                    }
                }
                break;
        }
    }
}

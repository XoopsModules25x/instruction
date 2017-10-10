<?php namespace Xoopsmodules\instruction;

//if (!defined("XOOPS_ROOT_PATH")) {
//	die("XOOPS root path not defined");
//}

include_once $GLOBALS['xoops']->path('include/common.php');

/**
 * Class Instruction
 * @package Xoopsmodules\instruction
 */
class Instruction extends \XoopsObject
{
    // constructor
    public function __construct()
    {
        //		$this->XoopsObject();
        $this->initVar('instrid', XOBJ_DTYPE_INT, null, false, 11);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0, false, 5);
        $this->initVar('uid', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('status', XOBJ_DTYPE_INT, 0, false, 1);
        $this->initVar('pages', XOBJ_DTYPE_INT, 0, false, 11);
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar('datecreated', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('dateupdated', XOBJ_DTYPE_INT, 0, false, 10);
        $this->initVar('metakeywords', XOBJ_DTYPE_TXTBOX, '', false);
        $this->initVar('metadescription', XOBJ_DTYPE_TXTBOX, '', false);

        // Нет в таблице
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 1, false);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * @return mixed
     */
    public function getNewInstertId()
    {
        $newEnreg = $GLOBALS['xoopsDB']->getInsertId();
        return $newEnreg;
    }

    // Получаем форму

    /**
     * @param bool|null|string $action
     * @return \XoopsThemeForm
     */
    public function getForm($action = false)
    {
        global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
        // Если нет $action
        if (false === $action) {
            $action = xoops_getenv('REQUEST_URI');
        }
        // Подключаем формы
        include_once $GLOBALS['xoops']->path('class/xoopsformloader.php');

        // Название формы
        $title = $this->isNew() ? sprintf(_AM_INSTRUCTION_FORMADDINSTR) : sprintf(_AM_INSTRUCTION_FORMEDITINSTR);

        // Форма
        $form = new \XoopsThemeForm($title, 'forminstr', $action, 'post', true);
        //$form->setExtra('enctype="multipart/form-data"');
        // Название инструкции
        $form->addElement(new \XoopsFormText(_AM_INSTRUCTION_TITLEC, 'title', 50, 255, $this->getVar('title')), true);
        // Категория
        $categoryHandler = new CategoryHandler;
        $criteria        = new \CriteriaCompo();
        $criteria->setSort('weight ASC, title');
        $criteria->setOrder('ASC');
        $instructioncat_arr = $categoryHandler->getall($criteria);
        unset($criteria);
        // Подключаем трей
        include_once $GLOBALS['xoops']->path('class/tree.php');
        $mytree = new \XoopsObjectTree($instructioncat_arr, 'cid', 'pid');

        // $form->addElement(new XoopsFormLabel(_AM_INSTRUCTION_CATC, $mytree->makeSelBox('cid', 'title', '--', $this->getVar('cid'), true)));
        $helper = Helper::getInstance();
        $module = $helper->getModule();

        if (Utility::checkVerXoops($module, '2.5.9')) {
            $mytree_select = $mytree->makeSelectElement('cid', 'title', '--', $this->getVar('cid'), true, 0, '', _AM_INSTRUCTION_CATC);
            $form->addElement($mytree_select);
        } else {
            $form->addElement(new \XoopsFormLabel(_AM_INSTRUCTION_CATC, $mytree->makeSelBox('cid', 'title', '--', $this->getVar('cid'), true)));
        }

        // Описание
        $form->addElement(Utility::getWysiwygForm(_AM_INSTRUCTION_DESCRIPTIONC, 'description', $this->getVar('description', 'e')), true);
        // Статус
        $form->addElement(new \XoopsFormRadioYN(_AM_INSTRUCTION_ACTIVEC, 'status', $this->getVar('status')), false);

        // Теги
        $dir_tag_ok = false;
        if (is_dir('../../tag') || is_dir('../tag')) {
            $dir_tag_ok = true;
        }
        // Если влючена поддержка тегов и есть модуль tag
        if (xoops_getModuleOption('usetag', 'instruction') && $dir_tag_ok) {
            $itemIdForTag = $this->isNew() ? 0 : $this->getVar('instrid');
            // Подключаем форму тегов
            include_once $GLOBALS['xoops']->path('modules/tag/include/formtag.php');
            // Добавляем элемент в форму
            $form->addElement(new \XoopsFormTag('tag', 60, 255, $itemIdForTag, 0));
        }

        // Мета-теги ключевых слов
        $form->addElement(new \XoopsFormText(_AM_INSTRUCTION_METAKEYWORDSC, 'metakeywords', 50, 255, $this->getVar('metakeywords')), false);
        // Мета-теги описания
        $form->addElement(new \XoopsFormText(_AM_INSTRUCTION_METADESCRIPTIONC, 'metadescription', 50, 255, $this->getVar('metadescription')), false);

        // Если мы редактируем категорию
        if (!$this->isNew()) {
            $form->addElement(new \XoopsFormHidden('instrid', $this->getVar('instrid')));
        }
        //
        $form->addElement(new \XoopsFormHidden('op', 'saveinstr'));
        // Кнопка
        $form->addElement(new \XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        return $form;
    }
}

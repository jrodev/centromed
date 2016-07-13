<?php

namespace Session\Form;
use Zend\Form\Annotation;
/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 * @Annotation\Name("User")
 */
class Login
{
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\ErrorMessage("My custom message!!!")
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Tipo documento"})
     */
    public $tipodoc;
    /**
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":"8","max":"8"}, "label":"8 caracteres"})
     * @Annotation\Options({"label":"Nro documento"})
     */
    public $nrodoc;
    /**
     * @Annotation\Type("Zend\Form\Element\Password")
     * @Annotation\Required({"required":"true" })
     * @Annotation\Filter({"name":"StripTags"})
     * @Annotation\Options({"label":"Password"})
     */
    public $pass;
    /**
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Submit"})
     */
    public $submit;
}
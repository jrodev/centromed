Composer Execute
===============================================================================
$ cd src/
$ php composer.phar install  (instala las dependencias segun composer.json)

NOTAS:
===============================================================================
Cada seccion es un controller con los contenidos asociados a actions:
solo en para IndexController se acoplan las secciones blog, cita y contactenos.

IndexController
    - index
    - blog
    - cita
    - contactenos

NosotrosController
    - Nostros
    - Nuestros Especialistas

EspecialidadesController
    - Fisioterapia Traumatologia
    - Fisioterapia Traumatologia
    - ...

EnfermedadesController
    - Fisioterapia Artritis reu..
    - Fisioterapia para tendinitis
    - ...

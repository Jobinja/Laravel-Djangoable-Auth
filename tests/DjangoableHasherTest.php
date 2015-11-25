<?php

class DjangoableHasherTest extends PHPUnit_Framework_TestCase
{
    public function test_it_can_check_django_passwords()
    {
        $checker = new \Jobinja\Djangoable\DjangoableHasher();
        $django = 'pbkdf2_sha256$20000$oeKBbG1rDmrL$ZQrhj9hRFzy7DQqAFNLAeEVv9W8pKNj0sHtPdnmSzcY=';
        $pass = '123456';
        $this->assertTrue($checker->checkForDjango($pass, $django));
    }
}
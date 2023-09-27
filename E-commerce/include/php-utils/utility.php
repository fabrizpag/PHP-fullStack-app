<?php
class utility
{
    static function cryptify($data): string
    {
        return md5(md5(md5(md5(md5($data)))));
    }
}

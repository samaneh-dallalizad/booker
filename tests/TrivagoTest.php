<?php


final class TrivagoTest extends \PHPUnit\Framework\TestCase
{
    public function testTrivagoCreateMustFailOnInvalidInput(): void
    {
        $b = new Booking();
        $b->trv_reference ='1';

        $s = new TrivagoHandler();
        $r = $s->create($b);
        $this->assertEquals(
            false,
            $r
        );
    }
}
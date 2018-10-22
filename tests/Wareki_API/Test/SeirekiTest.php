<?php

    namespace Wareki_API\Test;

    use Wareki_API\Seireki;

    class SeirekiTest extends \PHPUnit\Framework\TestCase {
        /**
         * @test
         */
        function construct_正常系() {
            new Seireki("2018-04-01");
            self::assertTrue(true);
        }

        /**
         * @test
         */
        function construct_正常系_年だけ() {
            new Seireki("2018");
            self::assertTrue(true);
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_月が小さい() {
            new Seireki("2018-00-01");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_月が大きい() {
            new Seireki("2018-13-01");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_日が小さい() {
            new Seireki("2018-04-00");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessageRegExp /\d+ is out of bound\./
         */
        function construct_日が大きい() {
            new Seireki("2018-04-32");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessage 20180401 is not a valid format.
         */
        function construct_値が不正() {
            new Seireki("20180401");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessage あいうえおかき is not a valid format.
         */
        function construct_値が不正_マルチバイト() {
            new Seireki("あいうえおかき");
        }

        /**
         * @test
         * @expectedException \InvalidArgumentException
         * @expectedExceptionMessage 2018-04 is not a valid format.
         */
        function construct_値が不正_月が存在() {
            new Seireki("2018-04");
        }

        /**
         * @test
         */
        function getter() {
            $seireki = new Seireki("2018-04-01");
            self::assertEquals(2018, $seireki->getYear());
            self::assertEquals(4, $seireki->getMonth());
            self::assertEquals(1, $seireki->getDate());
        }

        /**
         * @test
         */
        function getter_年だけ() {
            $seireki = new Seireki("2018");
            self::assertEquals(2018, $seireki->getYear());
            self::assertEquals(4, $seireki->getMonth());
            self::assertEquals(1, $seireki->getDate());
        }

        /**
         * @test
         */
        function toString_正常系() {
            $seireki = new Seireki("2018-04-01");
            self::assertEquals("2018-04-01", $seireki->__toString());
        }

        /**
         * @test
         */
        function toString_年だけ() {
            $seireki = new Seireki("2018");
            self::assertEquals("2018-04-01", $seireki->__toString());
        }

        /**
         * @test
         */
        function compare() {
            //年が小さい
            self::assertTrue(Seireki::compare(new Seireki('2017-04-01'), new Seireki('2018-04-01')) < 0);
            //年が等しい
            self::assertTrue(Seireki::compare(new Seireki('2018-04-01'), new Seireki('2018-04-01')) === 0);
            //年が大きい
            self::assertTrue(Seireki::compare(new Seireki('2019-04-01'), new Seireki('2018-04-01')) > 0);

            //月が小さい
            self::assertTrue(Seireki::compare(new Seireki('2018-03-01'), new Seireki('2018-04-01')) < 0);
            //月が大きい
            self::assertTrue(Seireki::compare(new Seireki('2018-05-01'), new Seireki('2018-04-01')) > 0);

            //日が小さい
            self::assertTrue(Seireki::compare(new Seireki('2018-04-01'), new Seireki('2018-04-02')) < 0);
            //日が大きい
            self::assertTrue(Seireki::compare(new Seireki('2018-04-02'), new Seireki('2018-04-01')) > 0);
        }

        /**
         * @test
         *
         * @throws \ReflectionException
         */
        function stringfy() {
            $seireki = new Seireki("2018-04-01");

            $reflection = new \ReflectionClass($seireki);
            $method = $reflection->getMethod('stringfy');
            $method->setAccessible(true);

            self::assertEquals("2018-04-01", $method->invoke($seireki));
        }
    }
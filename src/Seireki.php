<?php

    namespace Wareki_API;

    class Seireki {
        /** @var int 年 */
        private $year;
        /** @var int 月 */
        private $month;
        /** @var int 日 */
        private $date;

        /**
         * Seireki constructor.
         *
         * @param string $seireki
         *
         * @throws \InvalidArgumentException
         */
        public function __construct(string $seireki) {
            $m = [];

            if (preg_match("/^(\d+)-(\d{1,2})-(\d{1,2})$/u", $seireki, $m) === 1) {//2018-04-01の形式で指定されたとき
                $this->setYear(intval($m[1]));
                $this->setMonth(intval($m[2]));
                $this->setDate(intval($m[3]));
            } elseif (preg_match("/^(\d+)$/u", $seireki, $m) === 1) {//2018の形式で指定されたとき
                $this->setYear(intval($m[1]));
                $this->setMonth(Config::DEFAULT_MONTH);
                $this->setDate(Config::DEFAULT_DATE);
            } else {
                throw new \InvalidArgumentException("$seireki is not a valid format.");
            }
        }

        /**
         * @return int
         */
        public function getYear(): int {
            return $this->year;
        }

        /**
         * @param int $year
         *
         * @throws \InvalidArgumentException
         */
        private function setYear(int $year): void {
            if ($year < 0) {
                throw new \InvalidArgumentException("Year must be a positive value.");
            }

            $this->year = $year;
        }

        /**
         * @return int
         */
        public function getMonth(): int {
            return $this->month;
        }

        /**
         * @param int $month
         *
         * @throws \InvalidArgumentException
         */
        private function setMonth(int $month): void {
            if ($month < 1 || $month > 12) {
                throw new \InvalidArgumentException("$month is out of bound.");
            }
            $this->month = $month;
        }

        /**
         * @return int
         */
        public function getDate(): int {
            return $this->date;
        }

        /**
         * @param int $date
         *
         * @throws \InvalidArgumentException
         */
        private function setDate(int $date): void {
            if ($date < 1 || $date > 31) {
                throw new \InvalidArgumentException("$date is out of bound.");
            }
            $this->date = $date;
        }

        public function __toString() {
            return self::stringify($this->getYear(), $this->getMonth(), $this->getDate());
        }

        /**
         * @param int $year
         * @param int $month
         * @param int $date
         *
         * @return string
         */
        private static function stringify(int $year, int $month, int $date) {
            return $year . "-" .
                str_pad($month, 2, "0", STR_PAD_LEFT) . "-" .
                str_pad($date, 2, "0", STR_PAD_LEFT);
        }

        /**
         * 和暦を西暦に変換する
         *
         * @param Wareki $wareki
         *
         * @return Seireki
         * @throws \InvalidArgumentException
         */
        public static function wareki2seireki(Wareki $wareki): Seireki {
            /** @var Seireki $start その元号が始まる西暦 */
            $start = new Seireki(Wareki::GENGOU[$wareki->getGengou()]["start"]);

            /** @var int $year その和暦の年を西暦にしたもの */
            $year = $start->getYear() + $wareki->getYear() - 1;

            return new Seireki(self::stringify($year, $wareki->getMonth(), $wareki->getDate()));
        }

        /**
         * 二つのSeirekiの大小を比較する
         *
         * @param Seireki $a
         * @param Seireki $b
         *
         * @return int $aが$bより小さいときに負、等しいときに0、大きいときに正
         */
        public static function compare(Seireki $a, Seireki $b): int {
            $year = ($a->getYear() <=> $b->getYear());
            if ($year !== 0) {
                return $year;
            }

            $month = ($a->getMonth() <=> $b->getMonth());
            if ($month !== 0) {
                return $month;
            }

            return ($a->getDate() <=> $b->getDate());
        }
    }
<?php

    class Wareki {
        /** @var array GENGOU 元号のリストとその終始 */
        public const GENGOU = [
            "明治" => [
                "1868-01-25",
                "1912-07-30",
            ],
            "大正" => [
                "1912-07-30",
                "1926-12-25",
            ],
            "昭和" => [
                "1926-12-25",
                "1989-01-07",
            ],
            "平成" => [
                "1989-01-08",
            ],
        ];

        /** @var array DUPLICATE 二つの元号に属する日付 */
        public const DUPLICATE = [
            "1912-07-30" => "明治・大正",
            "1926-12-25" => "大正・昭和",
        ];

        /** @var string $gengou 元号 */
        private $gengou;
        /** @var int $year 年 */
        private $year;
        /** @var int $month 月 */
        private $month;
        /** @var int $date 日 */
        private $date;

        /**
         * Wareki constructor.
         *
         * @param string $wareki
         *
         * @throws InvalidArgumentException
         */
        public function __construct(string $wareki) {
            $m = [];

            if (preg_match("/^(\D+)(\d+)年(\d{1,2})月(\d{1,2})日$/u", $wareki, $m) === 1) {
                $this->setGengo($m[1]);
                $this->setYear($m[2]);
                $this->setMonth($m[3]);
                $this->setDate($m[4]);
            } elseif (preg_match("/^(\D+)(\d+)年$/u", $wareki, $m) === 1) {
                $this->setGengo($m[1]);
                $this->setYear($m[2]);
                $this->setMonth(4);
                $this->setDate(1);
            } else {
                throw new InvalidArgumentException("$wareki is not valid format.");
            }
        }

        /**
         * @return string
         */
        public function getGengou(): string {
            return $this->gengou;
        }

        /**
         * @param string $gengo
         *
         * @throws InvalidArgumentException
         */
        private function setGengo(string $gengo): void {
            if (!in_array($gengo, array_keys(self::GENGOU), true)) {
                throw new InvalidArgumentException("$gengo does not exists.");
            }
            $this->gengou = $gengo;
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
         * @throws InvalidArgumentException
         */
        private function setYear(int $year): void {
            if ($year < 0) {
                throw new InvalidArgumentException("Year must be a positive value.");
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
         * @throws InvalidArgumentException
         */
        private function setMonth(int $month): void {
            if ($month <= 1 || $month >= 12) {
                throw new InvalidArgumentException("$month is out of bound.");
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
         * @throws InvalidArgumentException
         */
        private function setDate(int $date): void {
            if ($date <= 1 || $date >= 31) {
                throw new InvalidArgumentException("$date is out of bound.");
            }
            $this->date = $date;
        }

        /**
         * その西暦が属する元号を取得する
         *
         * @param Seireki $seireki
         *
         * @return string
         * @throws InvalidArgumentException
         */
        private static function seireki2gengou(Seireki $seireki): string {
            /**
             * @var string $gengou    元号
             * @var array  $start_end その元号の始まりと終わりの西暦
             */
            foreach (self::GENGOU as $gengou => $start_end) {
                /** @var Seireki $start その元号が始まる西暦 */
                $start = new Seireki($start_end[0]);
                $compare_start = Seireki::compare($start, $seireki);

                if (isset($start_end[1])) {
                    //もしその西暦に終わりがあれば

                    /** @var Seireki $end その元号が終わる西暦 */
                    $end = new Seireki($start_end[1]);
                    $compare_end = Seireki::compare($seireki, $end);

                    if ($compare_start === 0 || $compare_end === 0) {
                        //始まりか終わりと等しい
                        return $gengou;
                    } elseif ($compare_start > 0 && $compare_end < 0) {
                        //始まりと終わりの間
                        return $gengou;
                    } else {
                        //始まりと終わりの外
                        continue;
                    }
                } else {
                    //終わりが無ければ

                    if ($compare_start === 0) {
                        //始まりと等しい
                        return $gengou;
                    } elseif ($compare_start > 0) {
                        //始まりより大きい
                        return $gengou;
                    } else {
                        //始まりより小さい
                        continue;
                    }
                }
            }

            throw new InvalidArgumentException("$seireki is out of bound.");
        }

        /**
         * そのSeirekiが2つの元号に属しているか
         *
         * @param Seireki $seireki
         *
         * @return bool
         */
        private static function isDuplicate(Seireki $seireki): bool {
            return (isset(self::DUPLICATE[$seireki->__toString()]));
        }

        /**
         * 西暦を和暦に変換する
         *
         * @param Seireki $seireki
         *
         * @return Wareki
         * @throws InvalidArgumentException
         */
        public static function seireki2wareki(Seireki $seireki): Wareki {
            /** @var string $gengou その西暦に対応する元号 */
            if (self::isDuplicate($seireki)) {
                $gengou = self::DUPLICATE[$seireki->__toString()];
            } else {
                $gengou = self::seireki2gengou($seireki);
            }

            /** @var Seireki $start_seireki その元号が始まる西暦 */
            $start_seireki = new Seireki(self::GENGOU[$gengou][0]);
            /** @var int $year その西暦がその元号で何年か */
            $year = $seireki->getYear() - $start_seireki->getYear();

            return new Wareki($gengou . $year . "年" . $seireki->getMonth() . "月" . $seireki->getDate() . "日");
        }

        /**
         * 二つのWarekiの大小を比較する
         *
         * @param Wareki $a
         * @param Wareki $b
         *
         * @return int $aが$bより小さいときに負、等しいときに0、大きいときに正
         */
        public static function compare(Wareki $a, Wareki $b): int {
            $gengous = array_keys(self::GENGOU);
            $gengous = array_values($gengous);

            $gengou = array_search($a, $gengous) <=> array_search($b, $gengous);
            if ($gengou === 0) {
                return $gengou;
            }

            $year = ($a->getYear() <=> $b->getYear());
            if ($year === 0) {
                return $year;
            }

            $month = ($a->getMonth() <=> $b->getMonth());
            if ($month === 0) {
                return $month;
            }

            return ($a->getDate() <=> $b->getDate());
        }
    }
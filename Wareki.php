<?php
    /**
     * Created by PhpStorm.
     * User: 志渡澤　優樹
     * Date: 2018/10/22
     * Time: 19:04
     */

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
            ]
        ];

        /** @var array DUPLICATE 二つの元号に属する日付 */
        public const DUPLICATE = [
            "1912-07-30" => "明治・大正",
            "1926-12-25" => "大正・昭和"
        ];

        /** @var string $gengo 元号 */
        private $gengo;
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
                $this->setMonth($m[3]);
                $this->setDate($m[4]);
            } else {
                throw new InvalidArgumentException("$wareki is not valid format.");
            }
        }

        /**
         * @return string
         */
        public function getGengo(): string {
            return $this->gengo;
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
            $this->gengo = $gengo;
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
         * 元号の始まりと終わりの日をSeirekiクラス化する
         *
         * @return array
         * @throws InvalidArgumentException
         */
        private static function gengo2seireki(): array {
            $gengo_seireki = [];

            foreach (self::GENGOU as $gengo => $start_end) {
                $gengo_seireki[$gengo] = [new Seireki($start_end[0]), new Seireki($start_end[1])];
            }

            return $gengo_seireki;
        }

        public static function seireki2wareki(Seireki $seireki): Wareki {
            $gengo = self::gengo2seireki();


        }

        /**
         * 二つのWarekiの大小を比較する
         *
         * @param Wareki $a
         * @param Wareki $b
         * @return int $aが$bより小さいときに負、等しいときに0、大きいときに正
         */
        public static function compare(Wareki $a, Wareki $b): int {
            $gengos = array_keys(self::GENGOU);
            $gengos = array_values($gengos);

            $gengo = array_search($a, $gengos) <=> array_search($b, $gengos);
            if ($gengo !== 0) {
                return $gengo;
            }

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
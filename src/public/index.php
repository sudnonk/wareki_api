<?php
    require_once __DIR__ . "/../../vendor/autoload.php";

    use Wareki_API\Wareki;
    use Wareki_API\Seireki;

    $wareki = filter_input(INPUT_GET, "wareki");
    $seireki = filter_input(INPUT_GET, "seireki");

    try {
        if ($wareki) {
            $seireki = Seireki::wareki2seireki(new Wareki($wareki));
            send_json(
                [
                    "status" => 200,
                    "gengou" => "西暦",
                    "year"   => $seireki->getYear(),
                    "month"  => $seireki->getMonth(),
                    "date"   => $seireki->getDate(),
                ]
            );
        } elseif ($seireki) {
            $wareki = Wareki::seireki2wareki(new Seireki($seireki));
            send_json(
                [
                    "status" => 200,
                    "gengou" => $wareki->getGengou(),
                    "year"   => $wareki->getYear(),
                    "month"  => $wareki->getMonth(),
                    "date"   => $wareki->getDate(),
                ]
            );
        } else {
            send_json(
                [
                    "status"  => 400,
                    "message" => "Please specify wareki or seireki.",
                ]
            );
        }
    } catch (InvalidArgumentException $e) {
        send_json(
            [
                "status"  => 400,
                "message" => $e->getMessage(),
            ]
        );
    }

    /**
     * データをJSON文字列として送信する
     *
     * @param array $data
     */
    function send_json(array $data) {
        if (ob_get_length() || ob_get_contents()) {
            ob_end_clean();
        }

        header("Content-type: application/json");
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }
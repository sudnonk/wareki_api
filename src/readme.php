<?php
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>
<h1>API仕様</h1>
<h2>対応範囲</h2>
明治元年01月25日(1868-01-25)～。明治、大正、昭和、平成<br>
平成30年10月22日現在、最新の元号は「平成」
<h2>西暦→和暦への変換</h2>
<table>
    <thead>
    <tr>
        <td>
            URL
        </td>
        <td>
            出力
        </td>
        <td>
            備考
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            GET /get_wareki?seireki=2018-04-01
        </td>
        <td>
                    <pre>
                    {
                        status: 200,
                        gengou: "平成",
                        year: 30,
                        month: 04,
                        day: 01
                    }
                    </pre>
        </td>
        <td>

        </td>
    </tr>
    <tr>
        <td>
            GET /get_wareki?seireki=1000-04-01
        </td>
        <td>
                    <pre>
                    {
                        status: 400,
                        message: "1000 is before Meiji."
                    }
                    </pre>
        </td>
        <td>
            1868年以前を指定した場合
        </td>
    </tr>
    <tr>
        <td>
            GET /get_wareki?seireki=2030-04-01
        </td>
        <td>
                    <pre>
                    {
                        gengou: "平成",
                        year: 42,
                        month: 04,
                        day: 01
                    }
                    </pre>
        </td>
    </tr>
    <tr>
        <td>
            GET /get_wareki?seireki=1989-01-07
        </td>
        <td>
                    <pre>
                    {
                        gengou: "昭和",
                        year: 64,
                        month: 01,
                        day: 07
                    }
                    </pre>
        </td>
        <td>
            昭和最後の日を指定した場合
        </td>
    </tr>
    <tr>
        <td>
            GET /get_wareki?seireki=1989-01-08
        </td>
        <td>
                    <pre>
                    {
                        gengou: "平成",
                        year: 01,
                        month: 01,
                        day: 08
                    }
                    </pre>
        </td>
        <td>
            平成最初の日を指定した場合
        </td>
    </tr>
    <tr>
        <td>
            GET /get_wareki?seireki=1989
        </td>
        <td>
                    <pre>
                    {
                        gengou: "平成",
                        year: 01,
                        month: 04,
                        day: 01
                    }
                    </pre>
        </td>
        <td>
            年だけ指定した場合は、その年の4月1日の値を返す。
        </td>
    </tr>
    </tbody>
</table>
<h2>和暦→西暦への変換</h2>
<table>
    <thead>
    <tr>
        <td>
            URL
        </td>
        <td>
            出力
        </td>
        <td>
            備考
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td>
            GET /get_seireki?wareki=平成30年4月1日
        </td>
        <td>
                    <pre>
                    {
                        status: 200,
                        gengou: "西暦",
                        year: 2018,
                        month: 04,
                        day: 01
                    }
                    </pre>
        </td>
        <td>

        </td>
    </tr>
    <tr>
        <td>
            GET /get_seireki?wareki=天保30年4月1日
        </td>
        <td>
                    <pre>
                    {
                        status: 400,
                        message: "天保 does not exists.."
                    }
                    </pre>
        </td>
        <td>
            対象の元号以外を指定した場合
        </td>
    </tr>
    <tr>
        <td>
            GET /get_seireki?wareki=平成42年04月01日
        </td>
        <td>
                    <pre>
                    {
                        status: 200,
                        gengou: "西暦",
                        year: 2030,
                        month: 04,
                        day: 01
                    }
                    </pre>
        </td>
    </tr>
    <tr>
        <td>
            GET /get_seireki?wareki=昭和300年04月01日
        </td>
        <td>
                    <pre>
                    {
                        status: 400,
                        message: "昭和 ends at 昭和64年01月07日."
                    }
                    </pre>
        </td>
        <td>
            その元号の範囲外を指定した場合
        </td>
    </tr>
    <tr>
        <td>
            GET /get_seireki?wareki=昭和64年01月08日
        </td>
        <td>
                    <pre>
                    {
                        status: 400,
                        message: "昭和 ends at 昭和64年01月07日."
                    }
                    </pre>
        </td>
        <td>
            その元号の範囲外を指定した場合
        </td>
    </tr>
    <tr>
        <td>
            GET /get_seireki?wareki=平成30年
        </td>
        <td>
                    <pre>
                     {
                        status: 200,
                        gengou: "西暦",
                        year: 2018,
                        month: 04,
                        day: 01
                    }
                    </pre>
        </td>
        <td>
            年のみを指定した場合は、その年の4月1日
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>


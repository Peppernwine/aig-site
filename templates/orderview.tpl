<table align='center' style='border:1px solid black;font-family:Tahoma,Arial,Helvetica,sans-serif;width:100%;background-color:{$backgroundColor};max-width:600px'>
    <tr>
        <td colspan='2' style='width:100%'>
            {$header}
        </td>
    </tr>
    <tr>
        <td style='display:{$highlightSectionDisplay};width:{$highlightSectionWidth};vertical-align:top;padding: 2px 2px 2px 2px'>
            {$highlights}
        </td>
        <td valign='top' style='width:{$mainSectionWidth}'>
            <table style='font-size:14px;width:100%;padding: 10px'>
                <tr>
                    <td style="padding: 5px">
                        {$customerInfo}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px">
                        {$reservationSummary}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px">
                        {$orderSummary}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px">
                        {$orderItems}
                    </td>
                </tr>
                <tr>
                    <td style="padding: 5px">
                        {$instructions}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
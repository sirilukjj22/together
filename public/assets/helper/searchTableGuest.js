function hideLabel() {
    console.log(1);

    // เปลี่ยน placeholder สำหรับฟิลด์ค้นหาทั้งหมดในทุก DataTable
    $('input[type="search"]').each(function () {
        $(this).attr("placeholder", "Type to search...");
        var searchID = $(this).attr('id');
        var text = searchID.split('-');
        var number = text[2];

        $('label[for="dt-length-'+ number +'"], label[for="'+ searchID +'"]').hide();

    });

    $(window).on("resize", function () {
        $.fn.dataTable
        .tables({ visible: true, api: true })
        .columns.adjust()
        .responsive.recalc();
    });

}

function deleteEntry(clicked_elmnt) {
    let elm_id = clicked_elmnt.id;
    let entry_id = elm_id.substring(6, elm_id.length);
    $.ajax({
        url: "delete.php",
        type: "POST",
        data: {
            'entry_id': entry_id
        },
        success: () => {
            window.location.reload();
        },
        error: () => {
            window.location.reload();
        }
    });
}
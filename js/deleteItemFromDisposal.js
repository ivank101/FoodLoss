
//完成

document.addEventListener('click', function(event) {
    if (event.target && event.target.nodeName == 'BUTTON' && event.target.classList.contains('deleteButton')) {
        var row = event.target.parentNode.parentNode;
        var disposalId = event.target.getAttribute('data-disposal-id');
        
        
        // SQL文を実行するためにPHPファイルにAJAXリクエストを送信する
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '../w_store_page/deleteItem.php', true);

        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    // SQL文の実行が成功した場合に行をテーブルから削除する
                    row.parentNode.removeChild(row);
                } else {
                    // SQL文の実行が失敗した場合のエラーハンドリング
                    console.log('SQLエラー: ' + xhr.responseText);
                }
            }
        };
        
        // disposalIdをPOSTリクエストのパラメータとして渡す
        var params = 'disposalId=' + encodeURIComponent(disposalId);
        
        // AJAXリクエストを送信する
        xhr.send(params);
    }
});

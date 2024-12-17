var editMode = false;
    function showEditForm() {
      // Hiển thị box nhập thông tin mới
      document.getElementById("editForm").style.display = "block";
      document.getElementById("storeInfo").style.display = "none";
      
      // Hiển thị nút "保存"
      document.getElementById("saveButton").style.display = "inline-block";
      
      // Ẩn nút "変更"
      document.getElementById("editButton").style.display = "none";
      
      editMode = true;
    }
    
    function saveChanges() {
      // // Xử lý lưu thay đổi ở đây
      
      // // Ẩn box nhập thông tin mới
      // document.getElementById("editForm").style.display = "none";
      // document.getElementById("editButton").style.display = "none";
      
      // // Ẩn nút "保存"
      // document.getElementById("saveButton").style.display = "none";
      
      // editMode = false;


      // Lấy thông tin mới từ các trường nhập
      var newStoreName = document.getElementById("newStoreName").value;
      var newStoreEmail = document.getElementById("newStoreEmail").value;
      var newStoreTel = document.getElementById("newStoreTel").value;
      var newStoreAddress = document.getElementById("newStoreAddress").value;

      // Gửi thông tin mới đến trang xử lý (save_changes.php)
      var form = document.getElementById("editForm");
      form.action = "store_information_update.php";
      form.method = "POST";
      form.submit();
    }
    
    function backButtonClicked() {
      if (editMode) {
        // Ẩn box nhập thông tin mới
        document.getElementById("editForm").style.display = "none";
        //Hiện storeInfo
        document.getElementById("storeInfo").style.display = "block";
        
        // Hiển thị nút "変更"
        document.getElementById("editButton").style.display = "inline-block";
        
        // Ẩn nút "保存"
        document.getElementById("saveButton").style.display = "none";
        editMode = false;
      } else {
        if (updateSuccess) {
          window.location.href = "getfood_disposal.php";
        } else {
          history.back(); // Quay lại trang trước đó
        }
      }
    }
    

    window.addEventListener('DOMContentLoaded', function() {
      var updateMessage = document.getElementById('updateMessage');
      var isSuccess = updateMessage.getAttribute('data-success');
  
      // Kiểm tra giá trị isSuccess và đặt màu sắc tương ứng
      if (isSuccess === 'true') {
        updateMessage.style.color = 'green'; // Màu xanh cho thành công
      } else if (isSuccess === 'false') {
        updateMessage.style.color = 'red'; // Màu đỏ cho thất bại
      }
  
      // Đặt thời gian hiển thị và sau đó ẩn đoạn text
      setTimeout(function() {
        updateMessage.style.display = 'none';
      }, 2000); // 3 giây
    });

    if (updateSuccess) {
        document.getElementById("backButton").textContent = "ホームページへ";
        document.getElementById("editButton").style.display = "none";
    } else {
      
    }
/*document.addEventListener("DOMContentLoaded", function () {
  const fileInput = document.querySelector(".file-input"),
    fileInputDiv = document.querySelector(".fileinput"),
    progressArea = document.querySelector(".progress-area"),
    uploadedArea = document.querySelector(".uploaded-area");

  fileInputDiv.addEventListener("click", () => {
    fileInput.click();
  });

  fileInput.onchange = ({ target }) => {
    let file = target.files[0];
    if (file) {
      let fileName = file.name;
      if (fileName.length >= 12) {
        let splitName = fileName.split('.');
        fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
      }
      uploadFile(fileName);
    }
  };

  function uploadFile(name) {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../../pages/submission/send_db.php");
    xhr.upload.addEventListener("progress", ({ loaded, total }) => {
      let fileLoaded = Math.floor((loaded / total) * 100);
      let fileTotal = Math.floor(total / 1000);
      let fileSize;
      (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";

      let progressHTML = `<li class="rows">
                            <i class="fas fa-file-alt"></i>
                            <div class="contents">
                              <div class="details">
                                <span class="name">${name} • Uploading</span>
                                <span class="percent">${fileLoaded}%</span>
                              </div>
                              <div class="progress-bar">
                                <div class="progress" style="width: ${fileLoaded}%"></div>
                              </div>
                            </div>
                          </li>`;

      uploadedArea.classList.add("onprogress");
      progressArea.innerHTML = progressHTML;

      if (loaded == total) {
        progressArea.innerHTML = "";
        let uploadedHTML = `<li class="rows">
                              <div class="content upload">
                                <i class="fas fa-file-alt"></i>
                                <div class="details">
                                  <span class="name">${name} • Uploaded</span>
                                  <span class="size">${fileSize}</span>
                                </div>
                              </div>
                              <i class="fas fa-check"></i>
                            </li>`;
        uploadedArea.classList.remove("onprogress");
        uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
      }
    });

    let data = new FormData();
    data.append("file", fileInput.files[0]); // ดึงไฟล์ที่เลือกมาใส่ใน FormData
    xhr.send(data);
  }
});*/

/*const form = document.querySelector("#upload-form"),
  fileInput = form.querySelector(".file-input"),
  progressArea = document.querySelector(".progress-area"),
  uploadedArea = document.querySelector(".uploaded-area");
form.addEventListener("click", () => {
  fileInput.click();
});
fileInput.onchange = ({ target }) => {
  let file = target.files[0];
  if (file) {
    let fileName = file.name;
    if (fileName.length >= 12) {
      let splitName = fileName.split('.');
      fileName = splitName[0].substring(0, 13) + "... ." + splitName[1];
    }
    uploadFile(fileName);
  }
}
function uploadFile(name) {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "../../pages/submission/upload.php");
  xhr.upload.addEventListener("progress", ({ loaded, total }) => {
    let fileLoaded = Math.floor((loaded / total) * 100);
    let fileTotal = Math.floor(total / 1000);
    let fileSize;
    (fileTotal < 1024) ? fileSize = fileTotal + " KB" : fileSize = (loaded / (1024 * 1024)).toFixed(2) + " MB";
    let progressHTML = `<li class="rows">
                          <i class="fas fa-file-alt"></i>
                          <div class="progress-con">
                            <div class="details">
                              <span class="name">${name} • Uploading</span>
                              <span class="percent">${fileLoaded}%</span>
                            </div>
                            <div class="progress-bar">
                              <div class="progress" style="width: ${fileLoaded}%"></div>
                            </div>
                          </div>
                        </li>`;
    uploadedArea.classList.add("onprogress");
    progressArea.innerHTML = progressHTML;
    if (loaded == total) {
      progressArea.innerHTML = "";
      let uploadedHTML = `<li class="rows">
                            <div class="progress-con upload">
                              <i class="fas fa-file-alt"></i>
                              <div class="details">
                                <span class="name">${name} • Uploaded</span>
                                <span class="size">${fileSize}</span>
                              </div>
                            </div>
                            <i class="fas fa-check"></i>
                          </li>`;
      uploadedArea.classList.remove("onprogress");
      uploadedArea.insertAdjacentHTML("afterbegin", uploadedHTML);
    }
  });
  let data = new FormData(form);
  xhr.send(data);
}*/

/*function setMethod(method) {
  const recipientInput = document.getElementById("recipient_id");
  const departmentSelect = document.getElementById("department_id");
  const extraOptions = document.getElementById("extraOptions");

  if (method === "individual") {
      recipientInput.style.display = "block";
      departmentSelect.style.display = "none";

      extraOptions.innerHTML = `
          <div class="form-check mt-2">
              <input class="form-check-input" type="checkbox" id="selectAllUsers" onchange="toggleAllUsers(this)">
              <label class="form-check-label" for="selectAllUsers">ส่งถึงทุกคน</label>
          </div>
      `;
  } else if (method === "group") {
      recipientInput.style.display = "none";
      departmentSelect.style.display = "block";
      extraOptions.innerHTML = "";
  }
}

function toggleAllUsers(checkbox) {
  const input = document.getElementById("recipient_id");
  if (checkbox.checked) {
      input.value = "ส่งถึงทุกคน";
      input.setAttribute("readonly", true);
  } else {
      input.value = "";
      input.removeAttribute("readonly");
  }
}

$(document).ready(function() {
  $("#recipient_id").autocomplete({
      source: "search_users.php", // ดึงข้อมูลจาก API
      minLength: 1, // พิมพ์ 1 ตัวอักษรขึ้นไปถึงแสดงผล
      select: function(event, ui) {
          $("#recipient_id").val(ui.item.value); // แสดงชื่อที่เลือก
          return false;
      }
  });
});

document.getElementById("add").addEventListener("submit", function(event) {
  event.preventDefault(); // ป้องกันการ submit ฟอร์มจริง
  
  const recipientInput = document.getElementById("recipient_id");
  const departmentSelect = document.getElementById("department_id");
  const purposeSelect = document.getElementById("purpose_id");
  const tableBody = document.querySelector("table tbody");
  
  let recipientName = "";
  if (recipientInput.style.display !== "none" && recipientInput.value.trim() !== "") {
      recipientName = recipientInput.value;
  } else if (departmentSelect.style.display !== "none" && departmentSelect.value !== "") {
      recipientName = departmentSelect.options[departmentSelect.selectedIndex].text;
  } else {
      alert("กรุณาเลือกผู้รับเอกสาร");
      return;
  }
  
  if (purposeSelect.value === "") {
      alert("กรุณาเลือกวัตถุประสงค์");
      return;
  }
  
  const purposeName = purposeSelect.options[purposeSelect.selectedIndex].text;
  
  // สร้างแถวใหม่ในตาราง
  const newRow = document.createElement("tr");
  newRow.innerHTML = `
      <td>${recipientName}</td>
      <td>${purposeName}</td>
      <td><button class="btn-close" aria-label="Close" onclick="removeRow(this)"></button></td>
  `;
  tableBody.appendChild(newRow);
  
  // รีเซ็ตค่าฟอร์มหลังจากเพิ่มข้อมูล
  recipientInput.value = "";
  departmentSelect.value = "";
  purposeSelect.value = "";
});*/

$(document).ready(function () {
  $('#example').Datatable();
});

function removeRow(button) {
  button.parentElement.parentElement.remove(); // ลบแถวที่กดปุ่มลบ
}

// Notification handling
$(document).ready(function() {
    // จัดการการคลิกที่การแจ้งเตือน
    $(document).on('click', '.notification-item', function(e) {
        e.preventDefault();
        const notificationId = $(this).data('notification-id');
        const $notificationItem = $(this);
        
        $.ajax({
            url: '../../pages/inbox/mark_notification_read.php',
            type: 'POST',
            data: { notification_id: notificationId },
            success: function(response) {
                try {
                    const result = JSON.parse(response);
                    if (result.success) {
                        // อัพเดท UI
                        $notificationItem.fadeOut(300, function() {
                            $(this).remove();
                            
                            // อัพเดทจำนวนการแจ้งเตือน
                            const $badge = $('.notification-badge');
                            const currentCount = parseInt($badge.text());
                            if (currentCount > 1) {
                                $badge.text(currentCount - 1);
                            } else {
                                $badge.remove();
                            }
                            
                            // ถ้าไม่มีการแจ้งเตือนเหลือ
                            if ($('.notification-item').length === 0) {
                                $('.notification-dropdown').find('li:not(.notification-header):not(.notification-footer)').html(
                                    '<li class="no-notifications">ไม่มีการแจ้งเตือนใหม่</li>'
                                );
                            }
                        });
                    }
                } catch (e) {
                    console.error('Error parsing response:', e);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });
});

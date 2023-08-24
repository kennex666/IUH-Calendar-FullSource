     const x01277 = true;
        
     function copyURL(txt) {

         var content = txt;

        content.select();
        document.execCommand('copy');

         alert("Copied!");
         
      }
	const x01629=()=>{if (!x01277)debugger;}
    setInterval(() => {x01629()}, 10);

    function getParameterByName(name, url = window.location.href) {
        name = name.replace(/[\[\]]/g, '\\$&');
        var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, ' '));
    }
    
    const schoolInformation = [
        {
            "sc": "iuh",
            "name": "Trường Đại học Công Nghiệp TP.HCM",
            "tc": "https://sv.iuh.edu.vn/tra-cuu-thong-tin.html",
            "urlChuan": "https://sv.iuh.edu.vn/tra-cuu/lich-hoc-tuan.html?k="
        },
        {
            "sc": "ut",
            "name": "Trường Đại học Giao Thông Vận Tải TP.HCM",
            "tc": "https://sv.ut.edu.vn/tra-cuu-thong-tin.html",
            "urlChuan": "https://sv.ut.edu.vn/tra-cuu/lich-hoc-tuan.html?k="
        },
        {
            "sc": "gdu",
            "name": "Trường Đại học Gia Định",
            "tc": "https://sinhvien.giadinh.edu.vn/tra-cuu-thong-tin.html",
            "urlChuan": "https://sinhvien.giadinh.edu.vn/tra-cuu/lich-hoc-tuan.html?k="
        },
        {
            "sc": "ntt",
            "name": "Trường Đại học Nguyễn Tất Thành",
            "tc": "https://phongdaotao.ntt.edu.vn/tra-cuu-thong-tin.html",
            "urlChuan": "https://phongdaotao.ntt.edu.vn/tra-cuu/lich-hoc-tuan.html?k="
        }
    ];

    const $ = document;

    var onChangeSchool = () =>{
        let scIndex = sltSchool.value
        if (scIndex == -1 || !schoolInformation[scIndex]) {
            txtKLichHoc.disabled = true;
            btnSubmit.disabled = false;
            formInputUrl.classList.add("d-none");
            lblTrangTraCuu.classList.add("d-none");
            return;
        }

        formInputUrl.classList.remove("d-none");
        lblTrangTraCuu.classList.remove("d-none");
        
        txtKLichHoc.disabled = false;
        btnSubmit.disabled = false;

        lblUrl.href = schoolInformation[scIndex].tc;
        lblKLichHoc.innerText = schoolInformation[scIndex].urlChuan;

    }


    var loadingSubmit = (e) => {
        if (e == true){
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
            btnSubmit.disabled = true;
        }else{
            btnSubmit.innerHTML = "Tạo lịch"
            btnSubmit.disabled = false;
        }
    }

    const getHostname = (url) => {
        // use URL constructor and return hostname
        return new URL(url).hostname;
    }

    var onSubmitFrmLicHoc = (e) => {
        e.preventDefault();

        loadingSubmit(true);
        
        getSchendar();

    }

    var showErrorDialog = (msg) => {
        errMsg.innerText = msg;
        alertMsg.classList.add("show");
        alertMsg.classList.remove("d-none");
        loadingSubmit(false);

    }

    //https://schendar.1boxstudios.com/v2.0/gateway/generateToken.js?sc=ut&k=pbekzkCNs99D4hRJsLvuTrKCzVt7AeUhb0YmHxmFM&o=gntk

    var getSchendar = async () => {

        if (!schoolInformation[sltSchool.value]) {
           showErrorDialog("Chưa chọn trường!");
            return;
        }
        if (!txtKLichHoc.value) {
            showErrorDialog("Chưa điền đủ thông tin, hãy kiểm tra lại!");
            return;
        }

        let getK = getParameterByName("k", txtKLichHoc.value);
        if (!getK) {
            getK = txtKLichHoc.value;
        }else {
            let hostInput = getHostname(txtKLichHoc.value);
            let host = getHostname(schoolInformation[sltSchool.value].urlChuan);
            if (hostInput.toLowerCase() != host.toLowerCase()){
                showErrorDialog("Kiểm tra lại trường học của bạn!");
                return;
            }
        }
        getK = getK.trim();

        if (getK.length < 10){
            showErrorDialog("Link lịch học không hợp lệ!");
            return;
        }
        txtKLichHoc.value = getK;

        let url = "https://schendar.1boxstudios.com/v2.0/gateway/generateToken.js?sc=" + schoolInformation[sltSchool.value].sc  + "&k=" + txtKLichHoc.value +"&o=gntk";
        
        try {
            let res = await fetch(url);
            let obj = await res.json();

            if (obj.status && obj.status == 200){
                modalShowLich.show();
                $.getElementById("urlLichHoc").innerText = obj.data.onlyStudy;
                $.getElementById("urlLichThi").innerText = obj.data.onlyExams;
                $.getElementById("urlLichHocThi").innerText = obj.data.normal;
                loadingSubmit(false);

            }else{
                showErrorDialog(obj.error);
                loadingSubmit(false);
                return;
            }
        }catch(err){
            showErrorDialog("Lỗi không xác định!");
            loadingSubmit(false);
            return;
        }

}
    
const getNotiVer = () => {
    return $.getElementById("modalThongTin").getAttribute("notiVer");
}

window.onload = () => {
    
        modalShowLich = new bootstrap.Modal(document.getElementById('modalShowLich'), {
            keyboard: false
        })

        modalThongTin = new bootstrap.Modal(document.getElementById('modalThongTin'), {
            keyboard: false
        })

        lblTrangTraCuu = $.getElementById("lblTrangTraCuu");
        errMsg = $.getElementById("errMsg");
        alertMsg = $.getElementById("alertMsg");

        formInputUrl = $.getElementById("formInputUrl");

        sltSchool = $.getElementById("sltSchool");
        sltSchool.onchange = onChangeSchool;

        txtKLichHoc = $.getElementById("txtKLichHoc");

        lblUrl = $.getElementById("lblUrl");
        lblKLichHoc = $.getElementById("lblKLichHoc");

        btnSubmit = $.getElementById("btnSubmit");

        frmGetLichHoc = $.getElementById("frmGetLichHoc");

        frmGetLichHoc.onsubmit = onSubmitFrmLicHoc;

        btnLichHocCopy = $.getElementById("btnLichHocCopy");
        btnLichHocCopy.onclick = () => {
            copyURL($.getElementById("urlLichHoc"));
        }

        btnLichThiCopy = $.getElementById("btnLichThiCopy");
        btnLichThiCopy.onclick = () => {
            copyURL($.getElementById("urlLichThi"));
        }

        btnLichHocThiCopy = $.getElementById("btnLichHocThiCopy");
        btnLichHocThiCopy.onclick = () => {
            copyURL($.getElementById("urlLichHocThi"));
        }

        
        if (!localStorage.getItem("notiVer") || localStorage.getItem("notikey").toLowerCase == getNotiVer()) {
            modalThongTin.show();
            localStorage.setItem("notiVer", getNotiVer());
        }

       
    }
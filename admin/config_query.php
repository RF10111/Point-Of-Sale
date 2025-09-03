<?php

class database
{
    var $host = "localhost";
    var $username = "root";
    var $password = "";
    var $database = "db_mystar";
    var $koneksi = "";

    function __construct()
    {
        $this->koneksi = mysqli_connect($this->host, $this->username, $this->password, $this->database);
        if (mysqli_connect_errno()) {
            echo "Koneksi database gagal: " . mysqli_connect_error();
        }
    }
// Customer Configuration //
    public function get_data_users($username)
    {
        $data = mysqli_query($this->koneksi, "SELECT * FROM tb_users WHERE username = '$username'");

        return $data;
    }
    
    public function tambah_customer($name, $registration_no, $chassis_no, $phone_no, $engine_no, $vin_no, $desc)
    {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_customer (name, registration_no, chassis_no, phone_no, engine_no, vin_no, description) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $name, $registration_no, $chassis_no, $phone_no, $engine_no, $vin_no, $desc);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function tampil_customers()
    {
        $data = $this->koneksi->query("SELECT * FROM tb_customer");
        $hasil = [];
        while ($row = $data->fetch_assoc()) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function get_customer_by_id($id)
    {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_customer WHERE id_customer = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_customer($id, $name, $registration_no, $chassis_no, $phone_no, $engine_no, $vin_no, $desc)
    {
        $stmt = $this->koneksi->prepare("UPDATE tb_customer SET name = ?, registration_no = ?, chassis_no = ?, phone_no = ?, engine_no = ?, vin_no = ?, description = ? WHERE id_customer = ?");
        $stmt->bind_param("sssssssi", $name, $registration_no, $chassis_no, $phone_no, $engine_no, $vin_no, $desc, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_customer($id)
    {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_customer WHERE id_customer = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    // Customer Configuration end//

    //Mekanik  Configuration//
    public function tambah_mekanik($name, $alamat, $phone_no)
    {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_mekanik (name, alamat, phone_no) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $alamat, $phone_no);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function tampil_mekanik()
    {
        $data = $this->koneksi->query("SELECT * FROM tb_mekanik");
        $hasil = [];
        while ($row = $data->fetch_assoc()) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function get_mekanik_by_id($id)
    {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_mekanik WHERE id_mekanik = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_mekanik($id, $name, $alamat, $phone_no)
    {
        $stmt = $this->koneksi->prepare("UPDATE tb_mekanik SET name = ?, alamat = ?, phone_no = ? WHERE id_mekanik = ?");
        $stmt->bind_param("sssi", $name, $alamat, $phone_no, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_mekanik($id)
    {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_mekanik WHERE id_mekanik = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    //Mekanik Configuration End//

    //supplier  Configuration//
    public function tambah_supplier($name, $alamat, $phone_no)
    {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_supplier (name, alamat, phone_no) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $alamat, $phone_no);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function tampil_supplier()
    {
        $data = $this->koneksi->query("SELECT * FROM tb_supplier");
        $hasil = [];
        while ($row = $data->fetch_assoc()) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function get_supplier_by_id($id)
    {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_supplier WHERE id_supplier = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_supplier($id, $name, $alamat, $phone_no)
    {
        $stmt = $this->koneksi->prepare("UPDATE tb_supplier SET name = ?, alamat = ?, phone_no = ? WHERE id_supplier = ?");
        $stmt->bind_param("sssi", $name, $alamat, $phone_no, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_supplier($id)
    {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_supplier WHERE id_supplier = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    //supplier Configuration End//

    //order config start//
public function update_stock_sp_new_order($id, $new_quantity, $new_harga_pokok) {
    // Ambil harga pokok saat ini
    $current_query = "SELECT harga_pokok FROM tb_sp_new WHERE id = ?";
    $stmt = $this->koneksi->prepare($current_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $current_result = $stmt->get_result()->fetch_assoc();
    $current_harga_pokok = $current_result['harga_pokok'];

    // Perbarui hanya jika harga pokok baru lebih besar
    if ($new_harga_pokok > $current_harga_pokok) {
        $query = "UPDATE tb_sp_new SET quantity = ?, harga_pokok = ? WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("idi", $new_quantity, $new_harga_pokok, $id);
    } else {
        $query = "UPDATE tb_sp_new SET quantity = ? WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("ii", $new_quantity, $id);
    }
    return $stmt->execute();
}

public function update_stock_sp_used_order($id, $new_quantity, $new_harga_pokok) {
    // Ambil harga pokok saat ini
    $current_query = "SELECT harga_pokok FROM tb_sp_used WHERE id = ?";
    $stmt = $this->koneksi->prepare($current_query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $current_result = $stmt->get_result()->fetch_assoc();
    $current_harga_pokok = $current_result['harga_pokok'];

    // Perbarui hanya jika harga pokok baru lebih besar
    if ($new_harga_pokok > $current_harga_pokok) {
        $query = "UPDATE tb_sp_used SET quantity = ?, harga_pokok = ? WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("idi", $new_quantity, $new_harga_pokok, $id);
    } else {
        $query = "UPDATE tb_sp_used SET quantity = ? WHERE id = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("ii", $new_quantity, $id);
    }
    return $stmt->execute();
}


public function cek_sp_used($sparepart_name, $part_number) {
    $query = "SELECT * FROM tb_sp_used WHERE sparepart_name = ? AND part_number = ?";
    $stmt = $this->koneksi->prepare($query);
    $stmt->bind_param("ss", $sparepart_name, $part_number);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

public function cek_sp_new($sparepart_name, $part_number) {
    $query = "SELECT * FROM tb_sp_new WHERE sparepart_name = ? AND part_number = ?";
    $stmt = $this->koneksi->prepare($query);
    $stmt->bind_param("ss", $sparepart_name, $part_number);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}


     public function tambah_order($supplier_name, $sparepart_name, $category, $arrival_date, $part_number, $quantity, $price, $harga_pokok, $total_payment, $remaining_payment, $payment_status) {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_order (supplier_name, sparepart_name, category, arrival_date, part_number, quantity, price, harga_pokok, total_payment, remaining_payment, payment_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)");
        $stmt->bind_param("sssssidddds", $supplier_name, $sparepart_name, $category, $arrival_date, $part_number, $quantity, $price, $harga_pokok, $total_payment, $remaining_payment, $payment_status);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function tampil_orders() {
        $data = $this->koneksi->query("SELECT * FROM tb_order ORDER BY arrival_date desc");
        $hasil = [];
        while ($row = $data->fetch_assoc()) {
            $hasil[] = $row;
        }
        return $hasil;
    }

    public function get_all_suppliers() {
    $query = "SELECT DISTINCT supplier_name FROM tb_order ORDER BY supplier_name";
    $result = $this->koneksi->query($query);

    $suppliers = [];
    while ($row = $result->fetch_assoc()) {
        $suppliers[] = $row['supplier_name'];
    }

    return $suppliers;
}

    
public function tampil_order_by($filter_month = '', $filter_year = '', $filter_supplier = '', $filter_payment_status = '') {
    // Prepare the base query with descending order
    $query = "SELECT * FROM tb_order WHERE 1=1";
    $params = [];
    $types = '';

    // Add month filter if provided
    if (!empty($filter_month)) {
        $query .= " AND MONTH(arrival_date) = ?";
        $params[] = $filter_month;
        $types .= 'i';
    }

    // Add year filter if provided
    if (!empty($filter_year)) {
        $query .= " AND YEAR(arrival_date) = ?";
        $params[] = $filter_year;
        $types .= 'i';
    }

    // Add supplier filter if provided
    if (!empty($filter_supplier)) {
        $query .= " AND supplier_name = ?";
        $params[] = $filter_supplier;
        $types .= 's';
    }

    // Add payment_status filter if provided
    if (!empty($filter_payment_status)) {
        $query .= " AND payment_status = ?";
        $params[] = $filter_payment_status;
        $types .= 's';
    }

    $query .= " ORDER BY arrival_date DESC";

    // Prepare and execute the statement
    $stmt = $this->koneksi->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    return '0';
}



    public function get_order_by_id($id) {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_order WHERE id_order = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_order($id, $total_payment, $remaining_payment, $payment_status) {
        $stmt = $this->koneksi->prepare("UPDATE tb_order SET  total_payment = ?, remaining_payment = ?, payment_status = ? WHERE id_order = ?");
        $stmt->bind_param("ddsi", $total_payment, $remaining_payment, $payment_status, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_order($id) {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_order WHERE id_order = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    //order config end//

    // tb_sp_used begin//
    // Fungsi untuk menambahkan ke tb_sp_used
    public function tambah_sp_used($sparepart_name, $part_number, $quantity, $harga_pokok, $tempat = '-') {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_sp_used (sparepart_name, part_number, quantity, harga_pokok, tempat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $sparepart_name, $part_number, $quantity, $harga_pokok, $tempat);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function tampil_sp_used($search_query = '') {
        // Prepare the base query
        $query = "SELECT * FROM tb_sp_used WHERE 1";
        $params = [];
        $types = '';
    
        // Add search query filter if provided
        if (!empty($search_query)) {
            $query .= " AND (sparepart_name LIKE ? OR part_number LIKE ?)";
            $search_param = '%' . $search_query . '%';
            $params[] = $search_param; // For sparepart_name
            $params[] = $search_param; // For part_number
            $types .= 'ss';
        }

        $query .= " ORDER BY sparepart_name ASC";
    
        // Prepare and execute the statement
        $stmt = $this->koneksi->prepare($query);
    
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Check if there are results
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    
        return '0';
    }

    public function get_order_by_id_used($id) {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_sp_used WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_sp_used($id, $sparepart_name, $part_number, $stock, $harga_pokok, $tempat) {
        $stmt = $this->koneksi->prepare("UPDATE tb_sp_used SET sparepart_name = ?, part_number = ?, quantity = ?, harga_pokok = ?, tempat = ? WHERE id = ?");
        $stmt->bind_param("ssidsi", $sparepart_name, $part_number, $stock, $harga_pokok, $tempat, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_sp_used($id) {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_sp_used WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    // tb_sp_used end//

    // tb_sp_new begin//
    public function tambah_sp_new($sparepart_name, $part_number, $quantity, $harga_pokok, $tempat = '-') {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_sp_new (sparepart_name, part_number, quantity, harga_pokok, tempat) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssids", $sparepart_name, $part_number, $quantity, $harga_pokok, $tempat);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
     public function tampil_sp_new($search_query = '') {
        // Prepare the base query
        $query = "SELECT * FROM tb_sp_new WHERE 1";
        $params = [];
        $types = '';
    
        // Add search query filter if provided
        if (!empty($search_query)) {
            $query .= " AND (sparepart_name LIKE ? OR part_number LIKE ?)";
            $search_param = '%' . $search_query . '%';
            $params[] = $search_param; // For sparepart_name
            $params[] = $search_param; // For part_number
            $types .= 'ss';
        }

        $query .= " ORDER BY sparepart_name ASC";
    
        // Prepare and execute the statement
        $stmt = $this->koneksi->prepare($query);
    
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
    
        $stmt->execute();
        $result = $stmt->get_result();
    
        // Check if there are results
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }
    
        return '0';
    }

    public function get_order_by_id_new($id) {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_sp_new WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_sp_new($id, $sparepart_name, $part_number, $stock, $harga_pokok, $tempat) {
        $stmt = $this->koneksi->prepare("UPDATE tb_sp_new SET sparepart_name = ?, part_number = ?, quantity = ?, harga_pokok = ?, tempat = ? WHERE id = ?");
        $stmt->bind_param("ssidsi", $sparepart_name, $part_number, $stock, $harga_pokok, $tempat, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_sp_new($id) {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_sp_new WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    // tb_sp_new end//

    //tb_jual start//
   // Tambahkan metode-metode berikut ke dalam class database

    // Tambah Penjualan
    public function tambah_jual(
        $customer_name, 
        $tgl_transaksi, 
        $sparepart_name, 
        $category, 
        $part_number, 
        $quantity, 
        $jumlah, 
        $harga_pokok, 
        $harga_jual, 
        $total, 
        $total_payment, 
        $remaining_payment, 
        $payment_status, 
        $profit
    ) {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_jual (
            customer_name, 
            tgl_transaksi, 
            sparepart_name, 
            category, 
            part_number, 
            quantity, 
            jumlah, 
            harga_pokok, 
            harga_jual, 
            total, 
            total_payment, 
            remaining_payment, 
            payment_status, 
            profit
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param(
            "sssssiidddddsd", 
            $customer_name, 
            $tgl_transaksi, 
            $sparepart_name, 
            $category, 
            $part_number, 
            $quantity, 
            $jumlah, 
            $harga_pokok, 
            $harga_jual, 
            $total, 
            $total_payment, 
            $remaining_payment, 
            $payment_status, 
            $profit
        );
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Update Penjualan
    public function update_jual(
        $id_jual, 
        $total_payment, 
        $remaining_payment, 
        $payment_status
    ) {
        $stmt = $this->koneksi->prepare("UPDATE tb_jual SET  
            total_payment = ?, 
            remaining_payment = ?, 
            payment_status = ?
        WHERE id_jual = ?");
        
        $stmt->bind_param(
            "ddsi",  
            $total_payment, 
            $remaining_payment, 
            $payment_status, 
            $id_jual
        );
        
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Hapus Penjualan
    public function delete_jual($id_jual) {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_jual WHERE id_jual = ?");
        $stmt->bind_param("i", $id_jual);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Ambil Data Penjualan berdasarkan ID
    public function get_jual_by_id($id_jual) {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_jual WHERE id_jual = ?");
        $stmt->bind_param("i", $id_jual);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Tampilkan Semua Penjualan
    public function tampil_jual($filter_month = '', $filter_year = '') {
        // Prepare the base query
        $query = "SELECT * FROM tb_jual WHERE 1=1";
        $params = [];
        $types = '';

        // Add month filter if provided
        if (!empty($filter_month)) {
            $query .= " AND MONTH(tgl_transaksi) = ?";
            $params[] = $filter_month;
            $types .= 'i';
        }

        // Add year filter if provided
        if (!empty($filter_year)) {
            $query .= " AND YEAR(tgl_transaksi) = ?";
            $params[] = $filter_year;
            $types .= 'i';
        }

        $query .= " ORDER BY tgl_transaksi DESC";

        // Prepare and execute the statement
        $stmt = $this->koneksi->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }

        return '0';
    }

    // Method untuk cek spare part used dengan normalisasi
    public function cek_sp_used_normalized($normalized_sparepart, $normalized_part_number) {
        $query = "SELECT * FROM tb_sp_used WHERE 
                LOWER(REGEXP_REPLACE(sparepart_name, '[^a-zA-Z0-9]', '')) = ? AND 
                LOWER(REGEXP_REPLACE(part_number, '[^a-zA-Z0-9]', '')) = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("ss", $normalized_sparepart, $normalized_part_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Method untuk cek spare part new dengan normalisasi
    public function cek_sp_new_normalized($normalized_sparepart, $normalized_part_number) {
        $query = "SELECT * FROM tb_sp_new WHERE 
                LOWER(REGEXP_REPLACE(sparepart_name, '[^a-zA-Z0-9]', '')) = ? AND 
                LOWER(REGEXP_REPLACE(part_number, '[^a-zA-Z0-9]', '')) = ?";
        $stmt = $this->koneksi->prepare($query);
        $stmt->bind_param("ss", $normalized_sparepart, $normalized_part_number);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Ambil Stok Spare Part Used
    public function get_stock_sp_used($sparepart_name, $part_number) {
        $stmt = $this->koneksi->prepare("SELECT quantity FROM tb_sp_used WHERE sparepart_name = ? AND part_number = ?");
        $stmt->bind_param("ss", $sparepart_name, $part_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['quantity'] : 0;
    }

    // Ambil Stok Spare Part New
    public function get_stock_sp_new($sparepart_name, $part_number) {
        $stmt = $this->koneksi->prepare("SELECT quantity FROM tb_sp_new WHERE sparepart_name = ? AND part_number = ?");
        $stmt->bind_param("ss", $sparepart_name, $part_number);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        return $row ? $row['quantity'] : 0;
    }

    // Update Stok Spare Part Used
    public function update_stock_sp_used($sparepart_name, $part_number, $new_stock) {
        $stmt = $this->koneksi->prepare("UPDATE tb_sp_used SET quantity = ? WHERE sparepart_name = ? AND part_number = ?");
        $stmt->bind_param("iss", $new_stock, $sparepart_name, $part_number);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Update Stok Spare Part New
    public function update_stock_sp_new($sparepart_name, $part_number, $new_stock) {
        $stmt = $this->koneksi->prepare("UPDATE tb_sp_new SET quantity = ? WHERE sparepart_name = ? AND part_number = ?");
        $stmt->bind_param("iss", $new_stock, $sparepart_name, $part_number);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    //tb_jual end//

    // reservasi begin
    // Fungsi untuk menambahkan data ke tb_reservasi
    public function tambah_reservasi($mekanik_name, $sparepart_name, $part_number, $quantity, $category, $customers) {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_reservasi (mekanik_name, sparepart_name, part_number, jumlah, category, customer) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssiss", $mekanik_name, $sparepart_name, $part_number, $quantity, $category, $customers);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Fungsi untuk memperbarui data di tb_reservasi
    public function update_reservasi($id, $mekanik_name, $sparepart_name, $part_number, $quantity, $category, $customers) {
        $stmt = $this->koneksi->prepare("UPDATE tb_reservasi SET mekanik_name = ?, sparepart_name = ?, part_number = ?, jumlah = ?, category = ?, customer = ? WHERE id = ?");
        $stmt->bind_param("sssissi", $mekanik_name, $sparepart_name, $part_number, $quantity, $category, $customers, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Fungsi untuk menghapus data dari tb_reservasi
    public function delete_reservasi($id) {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_reservasi WHERE id = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    // Fungsi untuk mendapatkan semua data dari tb_reservasi
    public function tampil_reservasi($filter_month = '', $filter_year = '') {
        // Prepare the base query
        $query = "SELECT * FROM tb_reservasi WHERE 1=1";
        $params = [];
        $types = '';

        // Add month filter if provided
        if (!empty($filter_month)) {
            $query .= " AND MONTH(created_at) = ?";
            $params[] = $filter_month;
            $types .= 'i';
        }

        // Add year filter if provided
        if (!empty($filter_year)) {
            $query .= " AND YEAR(created_at) = ?";
            $params[] = $filter_year;
            $types .= 'i';
        }

        $query .= " ORDER BY created_at DESC";

        // Prepare and execute the statement
        $stmt = $this->koneksi->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }

        return '0';
    }

    public function tampil_reservasi_detail_filter($created_at, $mekanik_name, $customer) {
    $query = "SELECT * FROM tb_reservasi 
              WHERE created_at = ? 
              AND mekanik_name = ? 
              AND customer = ?";
    
    // Gunakan prepared statement dengan mysqli
    $stmt = $this->koneksi->prepare($query);
    
    // Bind parameters dengan tipe data string
    $stmt->bind_param("sss", $created_at, $mekanik_name, $customer);
    
    // Eksekusi query
    $stmt->execute();
    
    // Ambil hasil query
    $result = $stmt->get_result();
    
    // Kembalikan data dalam bentuk array assosiatif
    return $result->fetch_all(MYSQLI_ASSOC);
    }
    // reservasi end

    // estimasi begin //
    public function tambah_estimasi(
    $customer_name, $registration_no, $chassis_no, $engine_no, $phone_no, $mileage, 
    $sparepart_name, $part_number, $quantity, $harga_jual, $total_harga, 
    $labour_name, $labour_cost, $total_sparepart, $total_labour, $grand_total) 
    {
    $stmt = $this->koneksi->prepare("
        INSERT INTO tb_estimasi (
            customer_name, registration_no, chassis_no, engine_no, phone_no, mileage, 
            sparepart_name, part_number, quantity, harga_jual, total_harga, 
            labour_name, labour_cost, total_sparepart, total_labour, grand_total
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssssiddsdddd", 
        $customer_name, $registration_no, $chassis_no, $engine_no, $phone_no, $mileage,
        $sparepart_name, $part_number, $quantity, $harga_jual, $total_harga, 
        $labour_name, $labour_cost, $total_sparepart, $total_labour, $grand_total
    );
    $result = $stmt->execute();
    $stmt->close();
    return $result;
    }

    public function tampil_estimasi($filter_month = '', $filter_year = '', $search_query = '') {
        // Prepare the base query
        $query = "SELECT * FROM tb_estimasi WHERE 1=1";
        $params = [];
        $types = '';

        // Add month filter if provided
        if (!empty($filter_month)) {
            $query .= " AND MONTH(created_at) = ?";
            $params[] = $filter_month;
            $types .= 'i';
        }

        // Add year filter if provided
        if (!empty($filter_year)) {
            $query .= " AND YEAR(created_at) = ?";
            $params[] = $filter_year;
            $types .= 'i';
        }

        if (!empty($search_query)) {
        $query .= " AND (customer_name LIKE ? OR registration_no LIKE ? OR chassis_no LIKE ?)";
        $search_param = '%' . $search_query . '%';
        $params[] = $search_param; // For customer_name
        $params[] = $search_param; // For registration_no
        $params[] = $search_param; // For chassis_no
        $types .= 'sss';
        }

        $query .= " ORDER BY created_at DESC";

        // Prepare and execute the statement
        $stmt = $this->koneksi->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }

        return '0';
    }

    public function tampil_estimasi_by_filter($created_at, $registration_no) {
    $query = "SELECT * FROM tb_estimasi 
              WHERE created_at = ? 
              AND registration_no = ?";
    
    // Gunakan prepared statement dengan mysqli
    $stmt = $this->koneksi->prepare($query);
    
    // Bind parameters dengan tipe data string
    $stmt->bind_param("ss", $created_at, $registration_no);
    
    // Eksekusi query
    $stmt->execute();
    
    // Ambil hasil query
    $result = $stmt->get_result();
    
    // Kembalikan data dalam bentuk array assosiatif
    return $result->fetch_all(MYSQLI_ASSOC);
    }


    // estimasi end //

    // invoice begin//
    public function tambah_invoice(
        $invoice_no, $customer_name, $registration_no, $chassis_no, $engine_no, $vin_no, $desc, $phone_no, $mileage,
        $sparepart_name, $part_number, $quantity, $harga_jual, $total_harga, $discount, 
        $labour_name, $labour_cost, $total_sparepart, $total_labour, $grand_total, $total_payment, $remaining_payment, $payment_status, $category, $received, $deadline, $spare_name, $spare_jml) 
    {
    $stmt = $this->koneksi->prepare("
        INSERT INTO tb_invoice (
            invoice_no, customer_name, registration_no, chassis_no, engine_no, vin_no, description, phone_no, mileage, 
            sparepart_name, part_number, quantity, harga_jual, total_harga, discount, 
            labour_name, labour_cost, total_sparepart, total_labour, grand_total, total_payment, remaining_payment, payment_status, category, received, deadline, manual_name, manual_jml
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "sssssssssssidddsddddddsssssd", 
        $invoice_no, $customer_name, $registration_no, $chassis_no, $engine_no, $vin_no, $desc, $phone_no, $mileage,
        $sparepart_name, $part_number, $quantity, $harga_jual, $total_harga, $discount, 
        $labour_name, $labour_cost, $total_sparepart, $total_labour, $grand_total, $total_payment, $remaining_payment, $payment_status, $category, $received, $deadline, $spare_name, $spare_jml
    );
    $result = $stmt->execute();
    $stmt->close();
    return $result;
    }

    public function update_invoice_payment($invoice_no, $total_payment, $remaining_payment, $payment_status) {
    $query = "UPDATE tb_invoice 
              SET total_payment = ?, 
                  remaining_payment = ?, 
                  payment_status = ? 
              WHERE invoice_no = ?";
    
    $stmt = $this->koneksi->prepare($query);
    $stmt->bind_param("ddss", $total_payment, $remaining_payment, $payment_status, $invoice_no, $created_at);
    
    return $stmt->execute();
    }

    
    public function tampil_invoice($filter_month = '', $filter_year = '', $search_query = '') {
    // Prepare the base query
    $query = "SELECT * FROM tb_invoice WHERE 1=1";
    $params = [];
    $types = '';

    // Add month filter if provided
    if (!empty($filter_month)) {
        $query .= " AND MONTH(created_at) = ?";
        $params[] = $filter_month;
        $types .= 'i';
    }

    // Add year filter if provided
    if (!empty($filter_year)) {
        $query .= " AND YEAR(created_at) = ?";
        $params[] = $filter_year;
        $types .= 'i';
    }

    // Add search query filter if provided
    if (!empty($search_query)) {
        $query .= " AND (customer_name LIKE ? OR registration_no LIKE ? OR chassis_no LIKE ?)";
        $search_param = '%' . $search_query . '%';
        $params[] = $search_param; // For customer_name
        $params[] = $search_param; // For registration_no
        $params[] = $search_param; // For chassis_no
        $types .= 'sss';
    }

    $query .= " ORDER BY created_at DESC";

    // Prepare and execute the statement
    $stmt = $this->koneksi->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    return '0';
}

    
    public function tampil_invoice_by_month($filter_month = '', $filter_year = '') {
        // Prepare the base query
        $query = "SELECT invoice_no, customer_name, registration_no, grand_total, payment_status, total_labour, total_sparepart, created_at 
              FROM tb_invoice 
              WHERE 1=1";

        $params = [];
        $types = '';

        // Add month filter if provided
        if (!empty($filter_month)) {
            $query .= " AND MONTH(created_at) = ?";
            $params[] = $filter_month;
            $types .= 'i';
        }

        // Add year filter if provided
        if (!empty($filter_year)) {
            $query .= " AND YEAR(created_at) = ?";
            $params[] = $filter_year;
            $types .= 'i';
        }

        $query .= " GROUP BY invoice_no, customer_name, registration_no, grand_total, payment_status, total_labour, total_sparepart, created_at ORDER BY created_at DESC";

        // Prepare and execute the statement
        $stmt = $this->koneksi->prepare($query);

        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        // Check if there are results
        if ($result->num_rows > 0) {
            $data = [];
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
            return $data;
        }

        return '0';
    }

    public function tampil_invoice_by_filter($invoice_no) {
    $query = "SELECT * FROM tb_invoice 
              WHERE invoice_no = ?";
    
    // Gunakan prepared statement dengan mysqli
    $stmt = $this->koneksi->prepare($query);
    
    // Bind parameters dengan tipe data string
    $stmt->bind_param("s", $invoice_no);
    
    // Eksekusi query
    $stmt->execute();
    
    // Ambil hasil query
    $result = $stmt->get_result();
    
    // Kembalikan data dalam bentuk array assosiatif
    return $result->fetch_all(MYSQLI_ASSOC);
    }
    // invoice end//

    //cashflow start
    public function tampil_cashflow($filter_day = '', $filter_month = '', $filter_year = '') {
    // Prepare the base query
    $query = "SELECT * FROM tb_cashflow WHERE 1=1";
    $params = [];
    $types = '';

    // Add day filter if provided
    if (!empty($filter_day)) {
        $query .= " AND DAY(tanggal) = ?";
        $params[] = $filter_day;
        $types .= 'i';
    }

    // Add month filter if provided
    if (!empty($filter_month)) {
        $query .= " AND MONTH(tanggal) = ?";
        $params[] = $filter_month;
        $types .= 'i';
    }

    // Add year filter if provided
    if (!empty($filter_year)) {
        $query .= " AND YEAR(tanggal) = ?";
        $params[] = $filter_year;
        $types .= 'i';
    }

    $query .= " ORDER BY tanggal DESC";

    // Prepare and execute the statement
    $stmt = $this->koneksi->prepare($query);

    // Bind parameters if there are any
    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    return '0'; // Return '0' if no data found
}


    public function tambah_cashflow($tanggal, $jumlah, $ket)
    {
        $stmt = $this->koneksi->prepare("INSERT INTO tb_cashflow (tanggal, jumlah, ket) VALUES (?, ?, ?)");
        $stmt->bind_param("sds", $tanggal, $jumlah, $ket);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function get_cashflow_by_id($id)
    {
        $stmt = $this->koneksi->prepare("SELECT * FROM tb_cashflow WHERE id_cashflow = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function update_cashflow($id, $tanggal, $jumlah, $ket)
    {
        $stmt = $this->koneksi->prepare("UPDATE tb_cashflow SET tanggal = ?, jumlah = ?, ket = ? WHERE id_cashflow = ?");
        $stmt->bind_param("sdsi", $tanggal, $jumlah, $ket, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }

    public function delete_cashflow($id)
    {
        $stmt = $this->koneksi->prepare("DELETE FROM tb_cashflow WHERE id_cashflow = ?");
        $stmt->bind_param("i", $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    //cashflow end

    // work order start
    public function tampil_wo($filter_month = '', $filter_year = '', $search_query = '') {
    // Prepare the base query
    $query = "SELECT * FROM tb_wo WHERE 1=1";
    $params = [];
    $types = '';

    // Add month filter if provided
    if (!empty($filter_month)) {
        $query .= " AND MONTH(created_at) = ?";
        $params[] = $filter_month;
        $types .= 'i';
    }

    // Add year filter if provided
    if (!empty($filter_year)) {
        $query .= " AND YEAR(created_at) = ?";
        $params[] = $filter_year;
        $types .= 'i';
    }

    // Add search query filter if provided
    if (!empty($search_query)) {
        $query .= " AND (customer_name LIKE ? OR registration_no LIKE ? OR chassis_no LIKE ?)";
        $search_param = '%' . $search_query . '%';
        $params[] = $search_param; // For customer_name
        $params[] = $search_param; // For registration_no
        $params[] = $search_param; // For chassis_no
        $types .= 'sss';
    }

    $query .= " ORDER BY created_at DESC";

    // Prepare and execute the statement
    $stmt = $this->koneksi->prepare($query);

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Check if there are results
    if ($result->num_rows > 0) {
        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }
        return $data;
    }

    return '0';
}

    public function tambah_wo(
    $customer_name, $registration_no, $chassis_no, $engine_no, $phone_no, $vin_no, $mileage, $desc, 
    $labour_name, $labour_ket) 
    {
    $stmt = $this->koneksi->prepare("
        INSERT INTO tb_wo (
            customer_name, registration_no, chassis_no, engine_no, phone_no, vin_no, mileage, desk,
            labour_name, labour_ket 
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->bind_param(
        "ssssssisss", 
        $customer_name, $registration_no, $chassis_no, $engine_no, $phone_no, $vin_no, $mileage, $desc,  
        $labour_name, $labour_ket
    );
    $result = $stmt->execute();
    $stmt->close();
    return $result;
    }

    public function get_work_order_by_filter($mileage, $registration_no, $created_at) {
    $query = "SELECT * FROM tb_wo
              WHERE mileage = ? 
              AND registration_no = ?
              AND created_at = ?";
    $stmt = $this->koneksi->prepare($query);
    
    // Bind parameters dengan tipe data yang sesuai
    $stmt->bind_param("iss", $mileage, $registration_no, $created_at);
    
    // Eksekusi query
    $stmt->execute();
    
    // Ambil hasil query
    $result = $stmt->get_result();
    
    // Kembalikan data dalam bentuk array assosiatif
    return $result->fetch_all(MYSQLI_ASSOC); // Mengembalikan satu baris data
}
 

}

?>


## Hướng dẫn Cài đặt
``
composer install
``

### Cấu hình

Copy `````.env.example````` -> `````.env`````


```
php artisan key:generate
```

### Tạo controller

```
 php artisan make:controller HomeController
```

### Tạo model

```
php artisan make:model NhanVienModel
```

## Quy ước

Model:
- ```danhSach```: Dành cho hiển thị danh sách
- ```them```: Dành cho thêm mới
- ```capNhat```: Dành cho cập nhật
- ```xoa```: Dành cho xoá
- ```chiTiet```: Danh cho lấy chi tiết

Controller:
- ```get<TenFuctionControler>```: Dành cho hiển thị danh sách
- ```put<TenFuctionControler>```: Dành cho thêm mới
- ```post<TenFuctionControler>```: Dành cho cập nhật
- ```delete<TenFuctionControler>```: Dành cho xoá


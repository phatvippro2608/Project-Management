create table account
(
    id_account  int auto_increment
        primary key,
    username    text                                  null,
    password    text                                  null,
    email       text                                  null,
    status      int                                   null,
    permission  int       default -1                  null,
    created_at  timestamp default current_timestamp() null,
    updated_at  timestamp default current_timestamp() null on update current_timestamp(),
    id_employee int                                   null
);

create table certificate_type
(
    id_certificate_type   int auto_increment
        primary key,
    certificate_type_name text                                  null,
    created_at            timestamp default current_timestamp() null,
    updated_at            timestamp default current_timestamp() null on update current_timestamp()
);

create table certificates
(
    id_certificate       int auto_increment
        primary key,
    id_employee          int                                   null,
    certificate          text                                  null,
    end_date_certificate date                                  null,
    id_type_certificate  int                                   null,
    created_at           timestamp default current_timestamp() null,
    updated_at           timestamp default current_timestamp() null on update current_timestamp()
);

create table contacts
(
    id_contact           int auto_increment
        primary key,
    phone_number         text                                  null,
    passport_number      text                                  null,
    passport_issue_date  datetime                              null,
    passport_expiry_date datetime                              null,
    passport_place_issue text                                  null,
    cic_number           text                                  null,
    cic_issue_date       datetime                              null,
    cic_expiry_date      datetime                              null,
    cic_place_issue      text                                  null,
    current_residence    text                                  null,
    permanent_address    text                                  null,
    medical_checkup_date datetime                              null,
    created_at           timestamp default current_timestamp() null,
    updated_at           timestamp default current_timestamp() null on update current_timestamp()
);

create table employees
(
    id_employee      int auto_increment
        primary key,
    employee_code    text                                  null,
    photo            text                                  null,
    last_name        text                                  null,
    first_name       text                                  null,
    en_name          text                                  null,
    gender           text                                  null,
    marital_status   text                                  null,
    date_of_birth    datetime                              null,
    national         text                                  null,
    military_service text                                  null,
    id_contact       int                                   null,
    created_at       timestamp default current_timestamp() null,
    updated_at       timestamp default current_timestamp() null on update current_timestamp()
);

create table history
(
    id_history   int auto_increment
        primary key,
    created_at   timestamp default current_timestamp() null,
    id_account   int                                   null,
    history_name text                                  null
);

create table job_category
(
    id_job_category   int auto_increment
        primary key,
    job_category_name text                                  null,
    created_at        timestamp default current_timestamp() null,
    updated_at        timestamp default current_timestamp() null on update current_timestamp()
);

create table job_country
(
    id_country   int auto_increment
        primary key,
    country_name text                                  null,
    created_at   timestamp default current_timestamp() null,
    updated_at   timestamp default current_timestamp() null on update current_timestamp()
);

create table job_detail
(
    id_job_detail        int auto_increment
        primary key,
    id_job_title         int                                   null,
    id_job_category      int                                   null,
    id_job_type_contract int                                   null,
    id_job_team          int                                   null,
    id_job_country       int                                   null,
    id_job_level         int                                   null,
    id_job_location      int                                   null,
    id_job_position      int                                   null,
    start_date           datetime                              null,
    end_date             datetime                              null,
    email                text                                  null,
    id_certificate       int                                   null,
    created_at           timestamp default current_timestamp() null,
    updated_at           timestamp default current_timestamp() null on update current_timestamp(),
    id_employee          int                                   null
);

create table job_level
(
    id_level   int auto_increment
        primary key,
    level_name text                                  null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp default current_timestamp() null on update current_timestamp()
);

create table job_location
(
    id_location   int auto_increment
        primary key,
    location_name text                                  null,
    created_at    timestamp default current_timestamp() null,
    updated_at    timestamp default current_timestamp() null on update current_timestamp()
);

create table job_position
(
    id_position   int auto_increment
        primary key,
    position_name text                                  null,
    created_at    timestamp default current_timestamp() null,
    updated_at    timestamp default current_timestamp() null on update current_timestamp()
);

create table job_team
(
    id_team    int auto_increment
        primary key,
    team_name  text                                  null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp default current_timestamp() null on update current_timestamp()
);

create table job_title
(
    id_job_title int auto_increment
        primary key,
    job_title    text null
);

create table job_type_contract
(
    id_type_contract   int auto_increment
        primary key,
    type_contract_name text                                  null,
    created_at         timestamp default current_timestamp() null,
    updated_at         timestamp default current_timestamp() null on update current_timestamp()
);

create table materials
(
    material_id   int auto_increment
        primary key,
    material_code varchar(50)    null,
    material_name varchar(255)   not null,
    description   text           null,
    brand         varchar(255)   null,
    origin        varchar(255)   null,
    unit          varchar(50)    null,
    quantity      int            null,
    unit_price    decimal(10, 2) null,
    labor_price   decimal(10, 2) null,
    total_price   decimal(10, 2) null,
    vat           decimal(5, 2)  null,
    delivery_time varchar(255)   null,
    warranty_time varchar(255)   null,
    remarks       text           null
);

create table projects
(
    project_id              int auto_increment
        primary key,
    project_name            text null,
    project_description     text null,
    project_address         text null,
    project_main_contractor text null,
    project_contact_name    text null,
    project_contact_website text null,
    project_contact_address int  null,
    project_contact_phone   text null,
    project_date_start      date null,
    project_date_end        date null
);


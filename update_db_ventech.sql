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
)
    charset = latin1;

create table account_import
(
    id     int auto_increment
        primary key,
    email  text null,
    ho_ten text null
)
    charset = latin1;

create table certificate_type
(
    id_certificate_type   int auto_increment
        primary key,
    certificate_type_name text                                  null,
    created_at            timestamp default current_timestamp() null,
    updated_at            timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

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
)
    charset = latin1;

create table contacts
(
    id_contact        int auto_increment
        primary key,
    phone_number      text                                  null,
    cic_number        text                                  null,
    cic_issue_date    date                                  null,
    cic_expiry_date   date                                  null,
    cic_place_issue   text                                  null,
    current_residence text                                  null,
    permanent_address text                                  null,
    created_at        timestamp default current_timestamp() null,
    updated_at        timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table department
(
    department_id   int auto_increment
        primary key,
    department_name varchar(255) not null,
    description     text         null
)
    charset = latin1;

create table employees
(
    id_employee      int auto_increment
        primary key,
    employee_code    text charset latin1                   null,
    photo            text charset latin1                   null,
    last_name        text                                  null,
    first_name       text                                  null,
    en_name          text charset latin1                   null,
    gender           text                                  null,
    marital_status   text                                  null,
    date_of_birth    date                                  null,
    national         text charset latin1                   null,
    military_service text charset latin1                   null,
    cv               text charset latin1                   null,
    id_contact       int                                   null,
    created_at       timestamp default current_timestamp() null,
    updated_at       timestamp default current_timestamp() null on update current_timestamp(),
    department_id    int                                   null,
    fired            text                                  null
);

create table history
(
    id_history      int auto_increment
        primary key,
    created_at      timestamp default current_timestamp() null,
    id_account      int                                   null,
    history_name    text                                  null,
    id_history_type int                                   null
)
    charset = latin1;

create table history_type
(
    id_history_type   int auto_increment
        primary key,
    history_type_name text null
);

create table job_category
(
    id_job_category   int auto_increment
        primary key,
    job_category_name text                                  null,
    created_at        timestamp default current_timestamp() null,
    updated_at        timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table job_country
(
    id_country   int auto_increment
        primary key,
    country_name text                                  null,
    created_at   timestamp default current_timestamp() null,
    updated_at   timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

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
    start_date           date                                  null,
    end_date             date                                  null,
    created_at           timestamp default current_timestamp() null,
    updated_at           timestamp default current_timestamp() null on update current_timestamp(),
    id_employee          int                                   null
)
    charset = latin1;

create table job_level
(
    id_level   int auto_increment
        primary key,
    level_name text                                  null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table job_location
(
    id_location   int auto_increment
        primary key,
    location_name text                                  null,
    created_at    timestamp default current_timestamp() null,
    updated_at    timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table job_position
(
    id_position   int auto_increment
        primary key,
    position_name text                                  null,
    created_at    timestamp default current_timestamp() null,
    updated_at    timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table job_team
(
    id_team    int auto_increment
        primary key,
    team_name  text                                  null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table job_title
(
    id_job_title int auto_increment
        primary key,
    job_title    text null
)
    charset = latin1;

create table job_type_contract
(
    id_type_contract   int auto_increment
        primary key,
    type_contract_name text                                  null,
    created_at         timestamp default current_timestamp() null,
    updated_at         timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table materials
(
    material_id   int auto_increment
        primary key,
    material_code varchar(50)    null,
    material_name varchar(255)   not null,
    description   mediumtext     null,
    brand         varchar(255)   null,
    origin        varchar(255)   null,
    unit          varchar(50)    null,
    quantity      int            null,
    unit_price    decimal(10, 2) null,
    labor_price   decimal(10, 2) null,
    total_price   decimal(10, 2) null,
    vat           decimal(5, 2)  null,
    delivery_time mediumtext     null,
    warranty_time mediumtext     null,
    remarks       mediumtext     null
)
    collate = utf8mb4_unicode_ci;

create table medical_checkup
(
    id_medical_checkup         int auto_increment
        primary key,
    medical_checkup_file       text                                  null,
    medical_checkup_issue_date date                                  null,
    id_employee                int                                   null,
    created_at                 timestamp default current_timestamp() null,
    updated_at                 timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table passport
(
    id_passport          int auto_increment
        primary key,
    passport_number      text                                  null,
    passport_issue_date  date                                  null,
    passport_expiry_date date                                  null,
    passport_place_issue text                                  null,
    passport_file        text                                  null,
    id_employee          int                                   null,
    created_at           timestamp default current_timestamp() null,
    updated_at           timestamp default current_timestamp() null on update current_timestamp()
)
    charset = latin1;

create table project_cost_group
(
    project_cost_group_id   int  not null
        primary key,
    project_cost_group_name text not null
)
    collate = utf8mb3_unicode_ci;

create table project_cost_datagroup
(
    project_cost_datagroup_id   int auto_increment
        primary key,
    project_cost_group_id       int  null,
    project_cost_groupdata_name text null,
    constraint cost_datagroup_cost_group_cost_group_id_fk
        foreign key (project_cost_group_id) references project_cost_group (project_cost_group_id)
            on update cascade on delete cascade
)
    charset = latin1;

create table project_cost
(
    project_cost_id           int auto_increment
        primary key,
    project_id                int    not null,
    project_cost_description  text   not null,
    project_cost_labor_qty    bigint null,
    project_cost_labor_unit   text   null,
    project_cost_budget_qty   bigint null,
    project_budget_unit       text   null,
    project_cost_labor_cost   bigint null,
    project_cost_misc_cost    bigint null,
    project_cost_perdiempay   bigint null,
    project_cost_remaks       text   null,
    project_cost_group_id     int    not null,
    project_cost_datagroup_id int    null,
    project_cost_ot_budget    int    null,
    constraint project_cost_project_cost_datagroup_project_cost_datagroup_id_fk
        foreign key (project_cost_datagroup_id) references project_cost_datagroup (project_cost_datagroup_id)
            on update cascade on delete cascade,
    constraint project_cost_project_cost_group_project_cost_group_id_fk
        foreign key (project_cost_group_id) references project_cost_group (project_cost_group_id)
            on update cascade on delete cascade
)
    collate = utf8mb3_unicode_ci;

create table project_daily_report_workday
(
    pdrw_id         int  not null,
    project_id      int  not null,
    project_workday date not null
)
    collate = utf8mb3_unicode_ci;

create table project_daily_report_worker
(
    pdrworker_id                    int        not null,
    pdrw_id                         int        not null,
    id_employee                     int        not null,
    pdrworker_overtime_start        time       null,
    pdrworker_overtime_end          time       null,
    pdrworker_work_name             date       not null,
    pdrworker_location              text       not null,
    pdrworker_quantity              int        not null,
    pdrworker_completed             tinyint(1) not null,
    pdrworker_interrupt             text       null,
    pdrworker_action_nextday        text       null,
    pdrworker_construction_schedule text       not null,
    pdrworker_remaks                text       null
)
    collate = utf8mb3_unicode_ci;

create table projects
(
    project_id                int auto_increment
        primary key,
    project_name              text charset latin1 null,
    project_description       text charset latin1 null,
    project_address           text charset latin1 null,
    project_main_contractor   text charset latin1 null,
    project_contact_name      text charset latin1 null,
    project_contact_website   text charset latin1 null,
    project_contact_phone     text charset latin1 null,
    project_date_start        date                null,
    project_date_end          date                null,
    project_status            int                 null,
    employees_id              int                 null,
    project_price_contingency bigint              null,
    project_contact_address   text                null
);

create table phases
(
    phase_id   int auto_increment
        primary key,
    phase_name varchar(255) not null,
    project_id int          not null,
    constraint phases_projects
        foreign key (project_id) references projects (project_id)
);

create table recent_project
(
    id_recent_project int auto_increment
        primary key,
    id_account        int                                   null,
    project_id        int                                   null,
    created_at        timestamp default current_timestamp() null
);

create table tasks
(
    task_id            int auto_increment
        primary key,
    phase_id           int          null,
    task_name          varchar(255) not null,
    progress           varchar(50)  null,
    start_date         date         null,
    end_date           date         null,
    initial_quantity   int          null,
    engineers          varchar(255) null,
    report_information text         null,
    area               varchar(255) null,
    actual_quantity    int          null,
    state              varchar(50)  null,
    difficulties       text         null,
    request            text         null,
    constraint tasks_ibfk_1
        foreign key (phase_id) references phases (phase_id)
);

create table sub_tasks
(
    sub_task_id        int auto_increment
        primary key,
    task_id            int          null,
    sub_task_name      varchar(255) not null,
    progress           varchar(50)  null,
    start_date         date         null,
    end_date           date         null,
    initial_quantity   int          null,
    engineers          varchar(255) null,
    report_information text         null,
    area               varchar(255) null,
    actual_quantity    int          null,
    state              varchar(50)  null,
    difficulties       text         null,
    request            text         null,
    constraint sub_tasks_ibfk_1
        foreign key (task_id) references tasks (task_id)
);

create index task_id
    on sub_tasks (task_id);

create index phase_id
    on tasks (phase_id);

create table team
(
    id_team    int auto_increment
        primary key,
    team_name  text                                  null,
    team_count int                                   null,
    created_by int                                   null,
    created_at timestamp default current_timestamp() null,
    updated_at timestamp default current_timestamp() null
)
    charset = latin1;

create table team_detail
(
    id_team_detail   int auto_increment
        primary key,
    id_employee      int null,
    id_team          int null,
    id_team_position int null
)
    charset = latin1;

create table team_position
(
    id_team_position int auto_increment
        primary key,
    position_name    text null,
    team_permission  int  null
)
    charset = latin1;


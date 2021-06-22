/*==============================================================*/
/* Table: app_user                                              */
/*==============================================================*/
create table app_user
(
    user_id  SERIAL                     not null,
    email    VARCHAR(255)               not null,
    password VARCHAR(255)               not null,
    role     VARCHAR(50) DEFAULT 'USER' not null,
    name     VARCHAR(50)                not null,
    constraint PK_APP_USER primary key (user_id)
);

/*==============================================================*/
/* Index: app_user_PK                                           */
/*==============================================================*/
create unique index app_user_PK on app_user (user_id);

/*==============================================================*/
/* Table: comment                                               */
/*==============================================================*/
create table comment
(
    comment_id   SERIAL                              not null,
    user_id      INT4                                not null,
    ticket_id    INT4                                not null,
    text         VARCHAR(512)                        not null,
    comment_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP not null,
    constraint PK_COMMENT primary key (comment_id)
);

/*==============================================================*/
/* Index: comment_PK                                            */
/*==============================================================*/
create unique index comment_PK on comment (comment_id);

/*==============================================================*/
/* Index: answer_FK                                             */
/*==============================================================*/
create index answer_FK on comment (user_id);

/*==============================================================*/
/* Index: refers_FK                                             */
/*==============================================================*/
create index refers_FK on comment (ticket_id);

/*==============================================================*/
/* Table: ticket                                                */
/*==============================================================*/
create table ticket
(
    ticket_id   SERIAL                                not null,
    user_id     INT4                                  not null,
    support_id  INT4                                  null,
    theme       VARCHAR(100)                          not null,
    content     VARCHAR(512)                          not null,
    file        VARCHAR(512)                          null,
    is_closed   BOOL        DEFAULT false             not null,
    status      VARCHAR(50) DEFAULT 'В обработке'     not null,
    ticket_date TIMESTAMP   DEFAULT CURRENT_TIMESTAMP not null,
    constraint PK_TICKET primary key (ticket_id)
);

/*==============================================================*/
/* Index: ticket_PK                                             */
/*==============================================================*/
create unique index ticket_PK on ticket (ticket_id);

/*==============================================================*/
/* Index: create_FK                                             */
/*==============================================================*/
create index create_FK on ticket (user_id);

/*==============================================================*/
/* Index: process_FK                                            */
/*==============================================================*/
create index process_FK on ticket (support_id);

alter table comment
    add constraint FK_COMMENT_ANSWER_APP_USER foreign key (user_id)
        references app_user (user_id)
        on delete restrict on update restrict;

alter table comment
    add constraint FK_COMMENT_REFERS_TICKET foreign key (ticket_id)
        references ticket (ticket_id)
        on delete restrict on update restrict;

alter table ticket
    add constraint FK_TICKET_CREATE_APP_USER foreign key (user_id)
        references app_user (user_id)
        on delete restrict on update restrict;

alter table ticket
    add constraint FK_TICKET_PROCESS_APP_USER foreign key (support_id)
        references app_user (user_id)
        on delete restrict on update restrict;

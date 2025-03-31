CREATE TABLE IF NOT EXISTS "migrations"(
  "id" integer primary key autoincrement not null,
  "migration" varchar not null,
  "batch" integer not null
);
CREATE TABLE IF NOT EXISTS "cache"(
  "key" varchar not null,
  "value" text not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "cache_locks"(
  "key" varchar not null,
  "owner" varchar not null,
  "expiration" integer not null,
  primary key("key")
);
CREATE TABLE IF NOT EXISTS "telescope_entries"(
  "sequence" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "batch_id" varchar not null,
  "family_hash" varchar,
  "should_display_on_index" tinyint(1) not null default '1',
  "type" varchar not null,
  "content" text not null,
  "created_at" datetime
);
CREATE UNIQUE INDEX "telescope_entries_uuid_unique" on "telescope_entries"(
  "uuid"
);
CREATE INDEX "telescope_entries_batch_id_index" on "telescope_entries"(
  "batch_id"
);
CREATE INDEX "telescope_entries_family_hash_index" on "telescope_entries"(
  "family_hash"
);
CREATE INDEX "telescope_entries_created_at_index" on "telescope_entries"(
  "created_at"
);
CREATE INDEX "telescope_entries_type_should_display_on_index_index" on "telescope_entries"(
  "type",
  "should_display_on_index"
);
CREATE TABLE IF NOT EXISTS "telescope_entries_tags"(
  "entry_uuid" varchar not null,
  "tag" varchar not null,
  foreign key("entry_uuid") references "telescope_entries"("uuid") on delete cascade,
  primary key("entry_uuid", "tag")
);
CREATE INDEX "telescope_entries_tags_tag_index" on "telescope_entries_tags"(
  "tag"
);
CREATE TABLE IF NOT EXISTS "telescope_monitoring"(
  "tag" varchar not null,
  primary key("tag")
);
CREATE TABLE IF NOT EXISTS "personal_access_tokens"(
  "id" integer primary key autoincrement not null,
  "tokenable_type" varchar not null,
  "tokenable_id" integer not null,
  "name" varchar not null,
  "token" varchar not null,
  "abilities" text,
  "last_used_at" datetime,
  "expires_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "personal_access_tokens_tokenable_type_tokenable_id_index" on "personal_access_tokens"(
  "tokenable_type",
  "tokenable_id"
);
CREATE UNIQUE INDEX "personal_access_tokens_token_unique" on "personal_access_tokens"(
  "token"
);
CREATE TABLE IF NOT EXISTS "site_settings"(
  "id" integer primary key autoincrement not null,
  "key" varchar not null,
  "value" text not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "roles"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "deleted_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "permissions"(
  "id" integer primary key autoincrement not null,
  "permission" varchar not null,
  "role_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("role_id") references "roles"("id") on delete cascade on update cascade
);
CREATE INDEX "permissions_role_id_index" on "permissions"("role_id");
CREATE TABLE IF NOT EXISTS "auth_updates"(
  "id" integer primary key autoincrement not null,
  "type" integer default '1',
  "attribute" varchar,
  "country_code" varchar,
  "code" varchar,
  "updatable_type" varchar not null,
  "updatable_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "auth_updates_updatable_type_updatable_id_index" on "auth_updates"(
  "updatable_type",
  "updatable_id"
);
CREATE TABLE IF NOT EXISTS "devices"(
  "id" integer primary key autoincrement not null,
  "device_type" varchar check("device_type" in('ios', 'android', 'web')),
  "device_id" text not null,
  "morph_id" integer not null,
  "morph_type" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "notifications"(
  "id" varchar not null,
  "type" varchar not null,
  "notifiable_type" varchar not null,
  "notifiable_id" integer not null,
  "data" text not null,
  "read_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  primary key("id")
);
CREATE INDEX "notifications_notifiable_type_notifiable_id_index" on "notifications"(
  "notifiable_type",
  "notifiable_id"
);
CREATE TABLE IF NOT EXISTS "socials"(
  "id" integer primary key autoincrement not null,
  "icon" varchar not null default 'default.png',
  "link" varchar,
  "name" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "complaints"(
  "id" integer primary key autoincrement not null,
  "user_name" varchar,
  "phone" varchar,
  "email" varchar,
  "complaint" text,
  "subject" text,
  "type" integer not null default '1',
  "complaintable_type" varchar,
  "complaintable_id" integer,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "complaints_complaintable_type_complaintable_id_index" on "complaints"(
  "complaintable_type",
  "complaintable_id"
);
CREATE TABLE IF NOT EXISTS "intro_sliders"(
  "id" integer primary key autoincrement not null,
  "image" varchar,
  "title" text,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intro_services"(
  "id" integer primary key autoincrement not null,
  "title" text,
  "description" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intro_fqs_categories"(
  "id" integer primary key autoincrement not null,
  "title" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intro_fqs"(
  "id" integer primary key autoincrement not null,
  "title" text,
  "description" text,
  "intro_fqs_category_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("intro_fqs_category_id") references "intro_fqs_categories"("id") on delete cascade
);
CREATE INDEX "intro_fqs_intro_fqs_category_id_index" on "intro_fqs"(
  "intro_fqs_category_id"
);
CREATE TABLE IF NOT EXISTS "intro_partners"(
  "id" integer primary key autoincrement not null,
  "image" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intro_messages"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "phone" varchar,
  "email" varchar,
  "message" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intros"(
  "id" integer primary key autoincrement not null,
  "image" varchar not null,
  "title" text not null,
  "description" text not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intro_how_works"(
  "id" integer primary key autoincrement not null,
  "title" text,
  "image" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "intro_socials"(
  "id" integer primary key autoincrement not null,
  "key" varchar,
  "url" text,
  "icon" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "log_activities"(
  "id" integer primary key autoincrement not null,
  "subject" varchar not null,
  "url" varchar not null,
  "method" varchar not null,
  "ip" varchar not null,
  "agent" varchar,
  "admin_id" integer,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("admin_id") references "admins"("id") on delete cascade
);
CREATE INDEX "log_activities_admin_id_index" on "log_activities"("admin_id");
CREATE TABLE IF NOT EXISTS "countries"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "key" varchar not null default '+966',
  "flag" varchar,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "fqs"(
  "id" integer primary key autoincrement not null,
  "question" text,
  "answer" text,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "images"(
  "id" integer primary key autoincrement not null,
  "image" varchar not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "s_m_s"(
  "id" integer primary key autoincrement not null,
  "name" varchar not null,
  "key" varchar not null,
  "sender_name" varchar not null,
  "user_name" varchar not null,
  "password" varchar not null,
  "active" tinyint(1) not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "jobs"(
  "id" integer primary key autoincrement not null,
  "queue" varchar not null,
  "payload" text not null,
  "attempts" integer not null,
  "reserved_at" integer,
  "available_at" integer not null,
  "created_at" integer not null
);
CREATE INDEX "jobs_queue_index" on "jobs"("queue");
CREATE TABLE IF NOT EXISTS "complaint_replays"(
  "id" integer primary key autoincrement not null,
  "complaint_id" integer not null,
  "replay" text not null,
  "replayer_id" integer not null,
  "replayer_type" varchar not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("complaint_id") references "complaints"("id") on delete cascade
);
CREATE INDEX "complaint_replays_complaint_id_index" on "complaint_replays"(
  "complaint_id"
);
CREATE TABLE IF NOT EXISTS "cities"(
  "id" integer primary key autoincrement not null,
  "name" text not null,
  "country_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("country_id") references "countries"("id") on delete cascade
);
CREATE TABLE IF NOT EXISTS "rooms"(
  "id" integer primary key autoincrement not null,
  "private" tinyint(1) not null default '0',
  "type" varchar not null default 'order',
  "order_id" integer,
  "createable_type" varchar not null,
  "createable_id" integer not null,
  "last_message_id" integer not null default '0',
  "created_at" datetime,
  "updated_at" datetime
);
CREATE INDEX "rooms_createable_type_createable_id_index" on "rooms"(
  "createable_type",
  "createable_id"
);
CREATE TABLE IF NOT EXISTS "room_members"(
  "id" integer primary key autoincrement not null,
  "room_id" integer not null,
  "memberable_type" varchar not null,
  "memberable_id" integer not null,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("room_id") references "rooms"("id") on delete cascade
);
CREATE INDEX "room_members_memberable_type_memberable_id_index" on "room_members"(
  "memberable_type",
  "memberable_id"
);
CREATE TABLE IF NOT EXISTS "messages"(
  "id" integer primary key autoincrement not null,
  "room_id" integer not null,
  "senderable_type" varchar not null,
  "senderable_id" integer not null,
  "body" text not null,
  "name" varchar,
  "type" varchar check("type" in('text', 'file', 'map', 'sound', 'image', 'video', 'invoice')) not null default 'text',
  "duration" double default '0.0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("room_id") references "rooms"("id") on delete cascade
);
CREATE INDEX "messages_senderable_type_senderable_id_index" on "messages"(
  "senderable_type",
  "senderable_id"
);
CREATE TABLE IF NOT EXISTS "message_notifications"(
  "id" integer primary key autoincrement not null,
  "room_id" integer not null,
  "message_id" integer not null,
  "userable_type" varchar not null,
  "userable_id" integer not null,
  "is_seen" tinyint(1) not null default '0',
  "is_sender" tinyint(1) not null default '0',
  "is_flagged" tinyint(1) not null default '0',
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("room_id") references "rooms"("id") on delete cascade,
  foreign key("message_id") references "messages"("id") on delete cascade
);
CREATE INDEX "message_notifications_userable_type_userable_id_index" on "message_notifications"(
  "userable_type",
  "userable_id"
);
CREATE TABLE IF NOT EXISTS "pages"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "slug" varchar not null,
  "content" text not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE UNIQUE INDEX "pages_slug_unique" on "pages"("slug");
CREATE TABLE IF NOT EXISTS "failed_jobs"(
  "id" integer primary key autoincrement not null,
  "uuid" varchar not null,
  "connection" text not null,
  "queue" text not null,
  "payload" text not null,
  "exception" text not null,
  "failed_at" datetime not null default CURRENT_TIMESTAMP
);
CREATE UNIQUE INDEX "failed_jobs_uuid_unique" on "failed_jobs"("uuid");
CREATE TABLE IF NOT EXISTS "sessions"(
  "id" varchar not null,
  "user_id" integer,
  "ip_address" varchar,
  "user_agent" text,
  "payload" text not null,
  "last_activity" integer not null,
  primary key("id")
);
CREATE INDEX "sessions_user_id_index" on "sessions"("user_id");
CREATE INDEX "sessions_last_activity_index" on "sessions"("last_activity");
CREATE TABLE IF NOT EXISTS "blogs"(
  "id" integer primary key autoincrement not null,
  "title" varchar not null,
  "content" text not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "contacts"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "invoices"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "quotes"(
  "id" integer primary key autoincrement not null,
  "created_at" datetime,
  "updated_at" datetime
);
CREATE TABLE IF NOT EXISTS "users"(
  "id" integer primary key autoincrement not null,
  "name" varchar,
  "avatar" varchar,
  "country_code" varchar not null default '966',
  "phone" varchar not null,
  "email" varchar,
  "country_id" integer,
  "city_id" integer,
  "password" varchar not null,
  "lang" varchar not null default 'ar',
  "active" tinyint(1) not null default '0',
  "is_blocked" tinyint(1) not null default '0',
  "is_notify" tinyint(1) not null default '1',
  "type" integer not null default '1',
  "code" varchar,
  "code_expire" datetime,
  "deleted_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("country_id") references "countries"("id"),
  foreign key("city_id") references "cities"("id")
);
CREATE TABLE IF NOT EXISTS "posts"(
  "id" integer primary key autoincrement not null,
  "user_id" integer not null,
  "title" text,
  "content" text,
  "slug" varchar not null,
  "privacy" varchar check("privacy" in('1', '2', '3')) not null default '1',
  "is_promoted" tinyint(1) not null default '0',
  "event_name" varchar,
  "event_date_time" datetime,
  "event_description" text,
  "repost_id" integer,
  "repost_text" text,
  "deleted_at" datetime,
  "created_at" datetime,
  "updated_at" datetime,
  foreign key("user_id") references "users"("id") on delete cascade,
  foreign key("repost_id") references "posts"("id") on delete cascade
);
CREATE UNIQUE INDEX "posts_slug_unique" on "posts"("slug");
CREATE INDEX "posts_repost_id_index" on "posts"("repost_id");
CREATE TABLE IF NOT EXISTS "admins"(
  "id" integer primary key autoincrement not null,
  "type" varchar check("type" in('admin', 'super_admin')) not null default 'admin',
  "name" varchar not null,
  "avatar" varchar,
  "email" varchar,
  "country_code" varchar,
  "phone" varchar,
  "password" varchar not null,
  "remember_token" varchar,
  "role_id" integer,
  "is_blocked" tinyint(1) not null default '0',
  "is_notify" tinyint(1) not null default '1',
  "deleted_at" datetime,
  "created_at" datetime,
  "updated_at" datetime
);

INSERT INTO migrations VALUES(1,'0001_01_01_000001_create_cache_table',1);
INSERT INTO migrations VALUES(2,'2018_08_08_100000_create_telescope_entries_table',1);
INSERT INTO migrations VALUES(3,'2019_12_14_000001_create_personal_access_tokens_table',1);
INSERT INTO migrations VALUES(4,'2020_03_15_204653_create_site_settings_table',1);
INSERT INTO migrations VALUES(5,'2020_08_20_224526_create_roles_table',1);
INSERT INTO migrations VALUES(6,'2020_08_20_225403_create_permissions_table',1);
INSERT INTO migrations VALUES(7,'2020_09_02_093416_create_auth_updates_table',1);
INSERT INTO migrations VALUES(8,'2020_09_02_134155_create_devices_table',1);
INSERT INTO migrations VALUES(9,'2020_09_20_083052_create_notifications_table',1);
INSERT INTO migrations VALUES(10,'2021_08_17_165636_create_socials_table',1);
INSERT INTO migrations VALUES(11,'2021_08_17_174312_create_complaints_table',1);
INSERT INTO migrations VALUES(12,'2021_08_23_135602_create_intro_sliders_table',1);
INSERT INTO migrations VALUES(13,'2021_08_23_165334_create_intro_services_table',1);
INSERT INTO migrations VALUES(14,'2021_08_23_174831_create_intro_fqs_categories_table',1);
INSERT INTO migrations VALUES(15,'2021_08_24_101015_create_intro_fqs_table',1);
INSERT INTO migrations VALUES(16,'2021_08_24_114741_create_intro_partners_table',1);
INSERT INTO migrations VALUES(17,'2021_08_24_125206_create_intro_messages_table',1);
INSERT INTO migrations VALUES(18,'2023_03_13_175552_create_intros_table',1);
INSERT INTO migrations VALUES(19,'2023_04_09_174959_create_intro_how_works_table',1);
INSERT INTO migrations VALUES(20,'2023_04_10_103032_create_intro_socials_table',1);
INSERT INTO migrations VALUES(21,'2023_04_11_164909_create_log_activities_table',1);
INSERT INTO migrations VALUES(22,'2023_04_12_133051_create_countries_table',1);
INSERT INTO migrations VALUES(23,'2023_04_12_172346_create_fqs_table',1);
INSERT INTO migrations VALUES(24,'2023_04_14_182157_create_images_table',1);
INSERT INTO migrations VALUES(25,'2023_04_15_105102_create_s_m_s_table',1);
INSERT INTO migrations VALUES(26,'2023_04_16_003820_create_jobs_table',1);
INSERT INTO migrations VALUES(27,'2023_10_19_105746_create_complaint_replays_table',1);
INSERT INTO migrations VALUES(28,'2023_10_21_144642_create_cities_table',1);
INSERT INTO migrations VALUES(29,'2023_10_22_000001_create_rooms_table',1);
INSERT INTO migrations VALUES(30,'2023_10_23_000002_create_room_members_table',1);
INSERT INTO migrations VALUES(31,'2023_10_24_000003_create_messages_table',1);
INSERT INTO migrations VALUES(32,'2023_10_25_000004_create_message_notifications_table',1);
INSERT INTO migrations VALUES(33,'2023_12_26_111903_create_pages_table',1);
INSERT INTO migrations VALUES(34,'2025_03_07_022150_create_failed_jobs_table',1);
INSERT INTO migrations VALUES(35,'2025_03_07_023344_create_sessions_table',1);
INSERT INTO migrations VALUES(36,'2025_03_28_185355_create_blogs_table',1);
INSERT INTO migrations VALUES(37,'2025_03_28_185355_create_contacts_table',1);
INSERT INTO migrations VALUES(38,'2025_03_28_185355_create_invoices_table',1);
INSERT INTO migrations VALUES(39,'2025_03_28_185356_create_quotes_table',1);
INSERT INTO migrations VALUES(40,'2025_03_28_185356_create_users_table',1);
INSERT INTO migrations VALUES(41,'2025_03_29_073154_create_posts_table',1);
INSERT INTO migrations VALUES(42,'2025_03_29_125629_create_admins_table',1);

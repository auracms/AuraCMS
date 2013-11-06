-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 04 Nov 2013 pada 04.09
-- Versi Server: 5.5.27
-- Versi PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `auracmsv3`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_actions`
--

DROP TABLE IF EXISTS `mod_actions`;
CREATE TABLE IF NOT EXISTS `mod_actions` (
  `id` int(20) NOT NULL AUTO_INCREMENT,
  `modul` varchar(20) NOT NULL DEFAULT '',
  `position` int(1) NOT NULL DEFAULT '0',
  `order` int(3) NOT NULL DEFAULT '1',
  `modul_id` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data untuk tabel `mod_actions`
--

INSERT INTO `mod_actions` (`id`, `modul`, `position`, `order`, `modul_id`) VALUES
(1, 'content', 1, 3, 17),
(2, 'content', 1, 4, 1),
(3, 'content', 1, 2, 8),
(4, 'content', 1, 1, 4),
(5, 'content', 1, 5, 22);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_admin`
--

DROP TABLE IF EXISTS `mod_admin`;
CREATE TABLE IF NOT EXISTS `mod_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(100) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `mod` int(1) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data untuk tabel `mod_admin`
--

INSERT INTO `mod_admin` (`id`, `menu`, `url`, `mod`, `ordering`) VALUES
(1, 'Dasbord', 'admin.php', 0, 1),
(2, 'Content', 'content', 1, 2),
(3, 'Video', 'video', 1, 3),
(4, 'Guestbook', 'guestbook', 1, 4),
(5, 'Users', 'users', 1, 5),
(6, 'Modul', 'modul', 1, 6),
(7, 'Settings', 'setting', 1, 7),
(8, 'Optimize', 'optimize', 1, 8),
(9, 'Logout', '?action=logout', 0, 21),
(10, 'Menu Manager', 'menu', 1, 9),
(19, 'Gallery Manager', 'gallery', 1, 19),
(12, 'Download', 'download', 1, 19),
(13, 'Web Links', 'weblinks', 1, 20),
(14, 'Polling', 'polling', 1, 19),
(15, 'Files Manager', 'files', 1, 18),
(20, 'Actions Manager', 'actions', 1, 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_content`
--

DROP TABLE IF EXISTS `mod_content`;
CREATE TABLE IF NOT EXISTS `mod_content` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL,
  `content` text NOT NULL,
  `type` enum('news','pages') NOT NULL DEFAULT 'news',
  `username` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` int(1) NOT NULL DEFAULT '0',
  `topic_id` int(3) NOT NULL DEFAULT '0',
  `image` text NOT NULL,
  `hits` int(250) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `seftitle` varchar(225) NOT NULL,
  `caption` varchar(1000) NOT NULL,
  `headline` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topik` (`topic_id`),
  KEY `tags` (`tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data untuk tabel `mod_content`
--

INSERT INTO `mod_content` (`id`, `title`, `content`, `type`, `username`, `email`, `date`, `published`, `topic_id`, `image`, `hits`, `tags`, `seftitle`, `caption`, `headline`) VALUES
(1, 'Tentang AuraCMS', '<p><a href="http://auracms.org"><strong>AuraCMS</strong></a> adalah hasil karya anak bangsa yang merupakan software CMS (Content Managemen System) untuk website yang berbasis PHP &amp; MySQL berlisensi GPL (General Public License).</p>\r\n<p>&nbsp;</p>\r\n<p>Dengan bentuk yang sederhana dan mudah ini diharapkan dapat digunakan oleh pemakai yang masih pemula sekalipun.</p>\r\n<p>&nbsp;</p>\r\n<p>Dan tak lupa bahwa software ini mungkin tak semuanya memenuhi harapan pemakai, oleh karena itu diharapkan adanya kritikan, sumbangan pikiran atau mungkin bentuk modifikasi dari para pengguna sekalian baik berupa modul maupun perubahan-perubahan lainnya yang dapat menjadikan auraCMS ini menjadi lebih baik.</p>\r\n<p>Terimakasih.</p>', 'pages', 'admin', '', '2013-10-22 20:46:11', 1, 0, '', 4, '', 'tentang-auracms', '', 0),
(2, 'Sejarah AuraCMS', '<p>Awal mulanya, AuraCMS itu berasal dari ide yang tercetus saat ingin membuat website dengan konten dinamis.</p>\r\n<p>&nbsp;</p>\r\n<p>Pada saat itu muncul ide untuk membuat kumpulan script PHP yang terintegrasi. Dan kemudian terbuatlah dua buah jenis script PHP tersebut yang satu menggunakan data berupa file text dan yang lainnya menggunakan database MySQL.</p>\r\n<p>&nbsp;</p>\r\n<p>Kemudian setelah dicoba ternyata data yang menggunakan database MySQL jauh lebih gampang dan tidak rumit dalam pengelolaannya sehingga yang menggunakan data berupa file text tidak dilanjutkan lagi. Setelah itu script yang menggunakan database MySQL tadi diberi nama "aura" dan karena merupakan software Content Management System maka nama lengkapnya "AuraCMS".</p>\r\n<p>&nbsp;</p>\r\n<p>Bekerjasama dengan Kioss Project versi pertama diluncurkan pada pertengahan tahun 2003. Kemudian disusul dengan versi versi berikutnya dan sempat "mandeg" beberapa waktu pada versi 1.3&amp;1.4. <br />Dan pada saat versi 1.5 terbit mulai banyak yang menyumbang modul ataupun modifikasi dan ide-ide baru sehingga terbit versi 1.6 beta sebagai versi percobaan.</p>\r\n<p><br />Ternyata dilaporkan bahwa pada versi 1.6 beta ini masih ada beberapa bug sehingga pada bulan Juli 2005 diterbitkan versi baru yang tidak beta lagi yaitu versi 1.61 dengan mengeliminasi bug pada versi 1.6 beta dan menambahkan beberapa fitur yang baru.</p>', 'pages', 'admin', '', '2013-10-22 20:48:57', 1, 0, '', 2, '', 'sejarah-auracms', '', 0),
(3, 'Donasi AuraCMS', '<p>Jika anda menyukai&nbsp;<a id="Y1365074S1">AuraCMS</a>&nbsp;ini dan ingin menyumbangkan&nbsp;<a id="Y1365074S3">dana</a>, Anda boleh menyumbangkan dana seberapapun kepada&nbsp;<a id="Y1365074S2">kami</a>, kami akan sangat menghargai sumbangan Anda.</p>\r\n<p>&nbsp;</p>\r\n<p>Donasi bisa ditransfer ke rekening kami di:</p>\r\n<p>&nbsp;</p>\r\n<p><strong><span>BNI Cabang Purwokerto</span><br />No. Rek. 0151742075<br />a.n. Iwan Susyanto</strong></p>\r\n<p><strong><br /></strong></p>\r\n<p>atau</p>\r\n<p>&nbsp;</p>\r\n<p>Anda bisa Membeli Domain dan Hosting untuk Website Anda nantinya di :</p>\r\n<p><a href="http://panturahost.co.id" target="_blank"><img style="display: block; margin-left: auto; margin-right: auto;" src="/auracmsv3/images/logopantura.png" alt="" width="500" height="185" /></a></p>\r\n<p style="text-align: center;">&nbsp;</p>\r\n<p><a href="http://duniamaya.web.id" target="_blank"><img style="display: block; margin-left: auto; margin-right: auto;" title="Dunia Maya" src="/auracmsv3/images/duniamaya.png" alt="Dunia Maya" width="434" height="100" /></a></p>\r\n<p>Terima kasih atas donasinya, semoga Allah SWT memberikan balasan<a id="Y1365074S4">yang</a>&nbsp;lebih baik kepada Anda.</p>', 'pages', 'admin', '', '2013-10-22 21:08:44', 1, 0, '', 5, '', 'donasi-auracms', '', 0),
(4, 'Sempat Unggul, AC Milan Belum Mampu Taklukkan Barcelona', '<p><span>Barcelona tampil mendominasi, namun serangan balik AC Milan berkali-kali membuat pertahanan Barca gusar. Dalam laga ini, tuan rumah mencetak gol lebih dulu lewat kerjasama cantik Kaka-Robinho. Selang 15 menit, giliran tim tamu yang mencetak gol lewat aksi brilian Lionel Messi.</span><br /><br /><span>Hasil imbang 1-1 terjadi di San Siro, Milan, Selasa (22/10). Serangan Barcelona yang bertubi-tubi masih belum mampu menembus tembok kokoh raksasa Italia ini.</span><br /><br /><strong>Babak pertama</strong><br /><span>Gol tercipta di menit ke-9 melalui kaki Robinho. Gerard Pique dan Javier Mascherano salah memprediksi datangnya pantulan bola. Penyerang Brasil ini pun memanfaatkan kesalahan ini dan melakukan umpan 1-2 dengan Kaka, sebelum menaklukkan Victor Valdes.</span><br /><br /><span>Barcelona yang tertinggal pun mencoba untuk membalas ketertinggalan. Kombinasi Xavi - Iniesta - Messi mencoba mengacaukan pertahanan&nbsp;</span><em>Rossoneri</em><span>. Namun, formasi Milan yang merapat saat bertahan membuat Barca kelimpungan.</span><br /><br /><span>Messi baru memecah kebuntuan di menit 23. Penyerang mungil ini menyambut umpan akurat Iniesta dan membawa bola masuk ke kotak penalti. Pemain Milan pun langsung mengepungnya seketika. Sayang, kelincahan dan kegesitan Messi masih terlalu sulit untuk digalau. Messi menaklukkan tiga pemain belakang Milan dan langsung mengirimkan sepakan terarah ke gawang Marco Amelia.</span><br /><br /><span>Laga kembali imbang. Barcelona yang mulai menguasai laga coba memulai inisiatif. Namun apa daya, pertahanan Milan terlalu rapat dan kedua tim memasuki jeda dengan skor 1-1.</span><br /><br /><strong>Babak kedua</strong><br /><span>Barca tampil lebih mendominasi kali ini, namun serangan balik Milan berkali-kali membuat pertahanan raksasa Catalan ini kerepotan. Serangan balik ccepat di menit 53 misalnya.</span><br /><br /><span>Serangan balik dari sisi kiri berujung pada umpan silang yang dikirim Sulley Muntari ke arah Robinho. Sayang, kebodohan dilakukan oleh eks pemain Manchester City ini. Robinho gagal memanfaatkan kesempatan dan malah mengirim bola keluar lapangan saat gawang Barca tengah menganga.</span><br /><br /><span>Selanjutnya giliran Barca yang mengancam. Mendapatkan umpan terobosan dari Xavi, Iniesta melepas tendangan ke gawang Milan namun masih bisa diamankan oleh Amelia. Menit 64, Mario Balotelli baru memasuki lapangan, menggantikan Robinho yang tampak kelelahan.</span><br /><br /><span>Barcelona masih menebar teror ke gawan Milan. Adriano jadi aktornya pada peluang kali ini. Umpan Messi sudah terukur tepat dan pergerakan bek sayap Brasil ini sudah tepat, namun tendangan volinya masih melebar dari gawang Milan.</span><br /><br /><span>Jelang laga berakhir, Muntari melakukan&nbsp;</span><em>solo-run</em><span>&nbsp;ke pertahanan Milan. Gelandang&nbsp;</span><em>Rossoneri</em><span>&nbsp;ini tampaknya juga tak dibiarkan begitu saja. Pique yang jadi barisan pertahanan langsung menekan gelandang ini. Sepakan Muntari yang dilepas setelahnya pun hanya bergulir pelan dan mudah diantisipasi oleh Valdes.</span><br /><br /><span>Wasit meniup peluit tanda berakhirnya pertandingan. Masuknya Balotelli tak mengubah apapun, memang masih terlalu dipaksakan mengingat sang striker masih cedera. Skor 1-1 pun bertahan hingga akhir. Hasil yang adil bagi kedua tim raksasa Eropa ini.</span><br /><br /><span>Barcelona sendiri masih berdiri di puncak klasemen Grup H dengan tujuh poin, selisih dua poin dengan AC Milan yang ada di belakang persis.</span><br /><br /><strong>SUSUNAN PEMAIN<br />Milan:</strong><span>&nbsp;Amelia, Mexes, Zapata, Abate, Constant, Muntari, Birsa, Montolivo, de Jong, Robinho, Kaka&nbsp;</span><strong><br /></strong><span>Subs: Coppola, Silvestre, Poli, Nocerino, Emanuelson, Matri, Balotelli.</span><strong><br />Barcelona:</strong><span>&nbsp;Valdes, Alves, Pique, Mascherano, Adriano, Sergio, Xavi, Iniesta, Alexis, Neymar, Messi</span><strong><br /></strong><span>Subs: Pinto, Montoya, Bartra, Song, Fabregas, Pedro, Tello.</span></p>', 'news', 'admin', '', '2013-10-23 04:55:01', 1, 2, '1382496901-331545hp2.jpg', 6, 'Champion', 'sempat-unggul-ac-milan-belum-mampu-taklukkan-barcelona', 'Sumber : Goal.Com', 1),
(5, '3 Langkah Mudah Memakai BBM di Android dan iPhone', '<p><span>Tanggal 21 dan 22 September 2013 akan menjadi hari penting bagi pecinta gadget yang telah lama menantikan kemunculan BBM (BlackBerry Messenger) lintas platform untuk Android dan iOS. Sebelum mengunduhnya, berikut tiga langkah mudah agar Anda dapat menggunakan BBM di dua platform tersebut di atas.</span></p>\r\n<p><strong>1. Perhatikan Versi Android dan iOS Anda</strong><br />BBM kompatibel untuk smartphone Android minimal Android 4.0 atau diatasnya dan untuk iOS minimal iOS 6. Sayangnya, bagi Anda yang menggunakan tablet PC Android dan iPad belum dapat menggunakan BBM.</p>\r\n<p>Untuk mengunduh BlackBerry Messenger yang memang disediakan gratis, Anda tinggal masuk ke www.bbm.com. Pilih menu install dan Anda akan dibawa ke Google Play Store atau Apple AppStore. Jika memang perangkat Anda sudah memenuhi syarat, maka Anda akan dapat meneruskan proses instalasi. Disarankan proses download dilakukan menggunakan koneksi Wi-Fi agar lebih stabil dan lebih cepat.</p>\r\n<p>Untuk versi Android, BBM bisa diunduh mulai tanggal 21 September pukul 18.00. Sedangkan versi iOS, bisa diunduh mulai tanggal 22 September pukul 00.01.</p>\r\n<p><strong>2. Harus memiliki BlackBerry ID&nbsp;</strong><br />Setelah BBM berhasil diunduh ke smartphone, Anda akan disambut dengan tampilan layar yang akan meminta Anda untuk mengisi Blackberry ID. Sampai di sini jika Anda belum memiliki Blackberry ID, Anda bisa langsung membuatnya. Setelah selesai membuat Blackberry ID Anda akan menerima balasan ke email yang akan menginfokan Anda telah berhasil membuat Blackberry ID.</p>\r\n<p>Perlu dicatat bahwa satu akun Blackberry ID hanya berlaku untuk satu handset saja. Jadi jika Anda telah memasukkan Blackberry ID yang sedang digunakan di perangkat Blackberry sebelumnya, secara otomatis seluruh data kontak yang ada di handset Blackberry sebelumnya akan berpindah ke perangkat Android atau iPhone yang baru.</p>\r\n<p><strong>3. Let&rsquo;s Chat&nbsp;</strong><br />Setelah registrasi Blackberry ID, pengguna iPhone atau Android akan mendapatkan PIN unik seperti layaknya PIN yang ada di handset BlackBerry untuk menggunakan BBM. Melalui nomor PIN ini, pengguna BBM lain bisa mengundang Anda untuk masuk ke daftar kontaknya.</p>\r\n<p>Begitu BBM aktif, maka Anda bisa langsung mengirim pesan via BBM seperti menggunakan perangkat Blackberry. Anda juga tetap bisa menggunakan BBM Group yang kini dibatasi 30 anggota dan Broadcast Message yang dibenci dan disukai pengguna BBM.</p>\r\n<p>Jadi sudah siap ber-BBM ria?</p>\r\n<p>Sumber : yangcanggih.com</p>\r\n<p><span><br /></span></p>', 'news', 'admin', '', '2013-10-26 00:07:17', 1, 1, '1382738837-BBM.jpg', 4, 'bbm', '3-langkah-mudah-memakai-bbm-di-android-dan-iphone', '3 Langkah Mudah Memakai BBM di Android dan iPhone', 1),
(6, 'Cara Mudah Menggunakan BBM di iPad', '<p>Simpang siur berita BBM di Android dan iOS memang kadang cukup membingungkan. Sebelumnya untuk platform iOS, dikatakan bahwa BBM hanya dapat digunakan di iPhone saja. Namun sebenarnya Anda yang ingin chatting menggunakan BBM di iPad juga dapat melakukan.</p>\r\n<p>Berikut langkah-langkah untuk memasang dan menggunakan BBM di iPad.</p>\r\n<p>1. Buka Apple AppStore dan cari BBM di kolom pencarian</p>\r\n<p><img title="Cara Mudah Menggunakan BBM di iPad" src="/auracmsv3/files/appstore-bbm.jpg" alt="Cara Mudah Menggunakan BBM di iPad" width="300" height="225" /></p>\r\n<p>2. Nantinya tidak akan ditemukan aplikasi BBM. Ubah kategori pencarian di kolom paling kiri menjadi iPhone Only.</p>\r\n<p>3. Aplikasi BBM akan muncul. Klik Free untuk meneruskan proses instalasi.</p>\r\n<p>4. Setelah proses instalasi selesai, jalankan aplikasi BBM di iPad.</p>\r\n<p>5. Masukkan alamat email untuk mendapatkan antrian.</p>\r\n<p><img title="Cara Mudah Menggunakan BBM di iPad" src="/auracmsv3/files/bbm-ipad-screen-1.jpg" alt="Cara Mudah Menggunakan BBM di iPad" width="300" height="400" /></p>\r\n<p><span>6. Setelah Anda mendapatkan email balasan untuk menggunakan BBM, jalankan kembali aplikasi BBM di iPad.</span></p>\r\n<p><img title="Cara Mudah Menggunakan BBM di iPad" src="/auracmsv3/files/bbm-ipad-screen-2.jpg" alt="Cara Mudah Menggunakan BBM di iPad" width="300" height="400" /></p>\r\n<p>7. Masukkan alamat email yang digunakan untuk mendapatkan antrian di kolom yang disediakan.</p>\r\n<p>8. BBM akan mengharuskan Anda membuat atau memasukkan BlackBerry ID (BB ID). Bagi yang pernah membuat dan menggunakan BB ID di ponsel BlackBerry, perlu diperhatikan bahwa satu BB ID hanya dapat digunakan di satu perangkat saja. Jika Anda menggunakan BB ID yang sama, maka BBM akan aktif di iPad dan non aktif di perangkat yang lama.</p>\r\n<p>9. Jika ingin membuat BB ID yang baru, pilih menu Sign Up.</p>\r\n<p>10. Isi form pembuatan BB ID.</p>\r\n<p><img title="Cara Mudah Menggunakan BBM di iPad" src="/auracmsv3/files/daftar-BB-ID.jpg" alt="Cara Mudah Menggunakan BBM di iPad" width="300" height="400" /></p>\r\n<p><span>11. Jalankan BBM dan masukkan username (email yang digunakan mendaftar BB ID) dan password BB ID yang baru saja dibuat.</span></p>\r\n<p><span><span>12. Aplikasi akan menampilkan PIN Anda dan nama yang akan ditampilkan di daftar kontak BBM. Untuk nomor PIN, tidak dapat dipilih dan diberikan secara acak. Sedangkan nama akan dapat diubah sesuai keinginan.</span></span></p>\r\n<p><span><span><span>Mudah kan? Selamat mencoba!!</span></span></span></p>\r\n<p><span><br /></span></p>', 'news', 'admin', '', '2013-10-26 00:43:25', 1, 1, '', 5, 'bbm', 'cara-mudah-menggunakan-bbm-di-ipad', '', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_content_ratings`
--

DROP TABLE IF EXISTS `mod_content_ratings`;
CREATE TABLE IF NOT EXISTS `mod_content_ratings` (
  `id` varchar(11) NOT NULL DEFAULT '',
  `total_votes` int(11) NOT NULL DEFAULT '0',
  `total_value` int(11) NOT NULL DEFAULT '0',
  `used_ips` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `mod_content_ratings`
--

INSERT INTO `mod_content_ratings` (`id`, `total_votes`, `total_value`, `used_ips`) VALUES
('1', 0, 0, ''),
('2', 0, 0, ''),
('3', 0, 0, ''),
('4', 0, 0, ''),
('5', 0, 0, ''),
('6', 0, 0, '');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_download`
--

DROP TABLE IF EXISTS `mod_download`;
CREATE TABLE IF NOT EXISTS `mod_download` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `cat_id` int(2) NOT NULL,
  `url` varchar(225) NOT NULL,
  `size` varchar(25) NOT NULL,
  `author` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` int(2) NOT NULL,
  `hits` varchar(5) NOT NULL DEFAULT '0',
  `seftitle` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seftitle` (`seftitle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `mod_download`
--

INSERT INTO `mod_download` (`id`, `title`, `description`, `cat_id`, `url`, `size`, `author`, `date`, `published`, `hits`, `seftitle`) VALUES
(1, 'asdas', '<p>asf</p>', 1, 'http://safsdgsdgsd.com', '324', 'admin', '2013-06-23 10:22:05', 1, '3', 0);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_download_cat`
--

DROP TABLE IF EXISTS `mod_download_cat`;
CREATE TABLE IF NOT EXISTS `mod_download_cat` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seftitle` (`seftitle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data untuk tabel `mod_download_cat`
--

INSERT INTO `mod_download_cat` (`id`, `title`, `description`, `seftitle`) VALUES
(1, 'AuraCMS', '<p>Berisi File Download AuraCMS dari beberapa versi terakhir samapai versi terbaru</p>', 'auracms'),
(2, 'Modul AuraCMS', '<p>Berisi modul-modul AuraCMS dari beberapa versi AuraCMS</p>', 'modul-auracms'),
(3, 'Tema AuraCMS', '<p>Beris tema-tema AuraCMS untuk Versi lama dan baru</p>', 'tema-auracms'),
(4, 'Plugin AuraCMS', '<p>Berisi Plugin-plugin AuraCMS</p>', 'plugin-auracms'),
(5, 'File lainss', '<p>File lains</p>', 'file-lainss');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_gallery`
--

DROP TABLE IF EXISTS `mod_gallery`;
CREATE TABLE IF NOT EXISTS `mod_gallery` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `images` varchar(225) NOT NULL,
  `album_id` int(2) NOT NULL,
  `caption` varchar(225) NOT NULL,
  `tanggal` datetime NOT NULL,
  `published` int(1) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_gallery_album`
--

DROP TABLE IF EXISTS `mod_gallery_album`;
CREATE TABLE IF NOT EXISTS `mod_gallery_album` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `album` varchar(100) NOT NULL,
  `published` int(1) NOT NULL DEFAULT '0',
  `username` varchar(100) NOT NULL,
  `tanggal` datetime NOT NULL,
  `seftitle` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seftitle` (`seftitle`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_guestbook`
--

DROP TABLE IF EXISTS `mod_guestbook`;
CREATE TABLE IF NOT EXISTS `mod_guestbook` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `website` varchar(50) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `city` varchar(100) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment` text NOT NULL,
  `answers` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_guestbook_config`
--

DROP TABLE IF EXISTS `mod_guestbook_config`;
CREATE TABLE IF NOT EXISTS `mod_guestbook_config` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `max_limit` int(4) NOT NULL,
  `char` int(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `mod_guestbook_config`
--

INSERT INTO `mod_guestbook_config` (`id`, `max_limit`, `char`) VALUES
(1, 20, 500);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_menu`
--

DROP TABLE IF EXISTS `mod_menu`;
CREATE TABLE IF NOT EXISTS `mod_menu` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL DEFAULT '',
  `url` varchar(225) NOT NULL DEFAULT '',
  `published` int(1) NOT NULL DEFAULT '0',
  `parentid` int(1) NOT NULL DEFAULT '0',
  `position` enum('top','block') NOT NULL,
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data untuk tabel `mod_menu`
--

INSERT INTO `mod_menu` (`id`, `title`, `url`, `published`, `parentid`, `position`, `ordering`) VALUES
(1, 'Home', 'index.html', 1, 0, 'top', 0),
(2, 'About Us', '#', 1, 0, 'top', 1),
(3, 'Tentang AuraCMS', 'pages-tentang-auracms.html', 1, 2, 'top', 2),
(4, 'Sejarah AuraCMS', 'pages-sejarah-auracms.html', 1, 2, 'top', 3),
(5, 'Site Credit', '#', 1, 2, 'top', 4),
(6, 'Donasi AuraCMS', 'pages-donasi-auracms.html', 1, 2, 'top', 5),
(7, 'Gallery Foto', 'gallery.html', 1, 0, 'top', 6),
(8, 'Bukutamu', 'guestbook.html', 1, 0, 'top', 7),
(9, 'Weblinks', 'weblinks.html', 1, 0, 'top', 8),
(10, 'Download', 'download.html', 1, 0, 'top', 9),
(11, 'Hubungi Kami', 'contact.html', 1, 0, 'top', 10);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_modul`
--

DROP TABLE IF EXISTS `mod_modul`;
CREATE TABLE IF NOT EXISTS `mod_modul` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `modul` varchar(100) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `setup` varchar(50) NOT NULL DEFAULT '',
  `position` tinyint(2) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '0',
  `ordering` int(5) NOT NULL DEFAULT '1',
  `type` enum('block','module') NOT NULL DEFAULT 'module',
  `spesial` enum('yes','no') NOT NULL DEFAULT 'no',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Dumping data untuk tabel `mod_modul`
--

INSERT INTO `mod_modul` (`id`, `modul`, `content`, `setup`, `position`, `published`, `ordering`, `type`, `spesial`) VALUES
(1, 'Calendar', 'mod/calendar/calendar_block.php', '', 0, 1, 5, 'module', 'no'),
(8, 'Polling Website', 'mod/polling/polling_block.php', '', 0, 1, 4, 'module', 'no'),
(4, 'Pantura Hosting', '<p style="text-align: center;"><a title="Pantura Hosting" href="http://panturahost.co.id/" target="_blank"><img title="Pantura Hosting" src="/auracmsv3/images/panturahost.png" alt="Pantura Hosting" width="300" height="250" /></a></p>', '', 1, 1, 2, 'block', 'no'),
(5, 'Topik Berita', 'mod/content/topic.php', '', 1, 1, 3, 'module', 'no'),
(10, 'Arsip Berita', 'mod/content/arsip.php', '', 1, 1, 3, 'module', 'no'),
(22, 'Statistik Website', 'mod/statistik/vcounter.php', '', 1, 1, 5, 'module', 'no'),
(16, 'Random Links', 'mod/weblinks/randomlinks.php', '', 0, 1, 3, 'module', 'no'),
(17, 'Artikel Terakhir', 'mod/content/latest.php', '', 1, 1, 4, 'module', 'no');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_polling`
--

DROP TABLE IF EXISTS `mod_polling`;
CREATE TABLE IF NOT EXISTS `mod_polling` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `content` varchar(225) NOT NULL,
  `published` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `mod_polling`
--

INSERT INTO `mod_polling` (`id`, `content`, `published`) VALUES
(3, '{"question":"Apakah Website ini Menarik?","answers":{"Sangat Menarik":21,"Menarik":"7","Tidak Menarik":"5"}}', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_setting`
--

DROP TABLE IF EXISTS `mod_setting`;
CREATE TABLE IF NOT EXISTS `mod_setting` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `keyword` text NOT NULL,
  `description` text NOT NULL,
  `slogan` text NOT NULL,
  `status` int(2) NOT NULL DEFAULT '1',
  `url` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `mailtype` varchar(10) NOT NULL DEFAULT 'mail',
  `smtpport` int(3) NOT NULL,
  `smtphost` varchar(100) NOT NULL,
  `smtpusername` varchar(100) NOT NULL,
  `smtppassword` varchar(100) NOT NULL,
  `smtpssl` varchar(5) NOT NULL DEFAULT 'ssl',
  `signature` text NOT NULL,
  `systememailsfromname` varchar(100) NOT NULL,
  `systememailsfromemail` varchar(100) NOT NULL,
  `themes` varchar(50) NOT NULL,
  `admin_themes` varchar(50) NOT NULL,
  `name_blocker` text NOT NULL,
  `email_blocker` text NOT NULL,
  `timeplus` int(6) NOT NULL DEFAULT '3600',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `mod_setting`
--

INSERT INTO `mod_setting` (`id`, `title`, `keyword`, `description`, `slogan`, `status`, `url`, `email`, `mailtype`, `smtpport`, `smtphost`, `smtpusername`, `smtppassword`, `smtpssl`, `signature`, `systememailsfromname`, `systememailsfromemail`, `themes`, `admin_themes`, `name_blocker`, `email_blocker`, `timeplus`) VALUES
(1, 'Dinas Kesehatan Kabupaten Gorontalo', 'freeware,shareware,download,software,mobile,phone,ipod', 'Softponsel - Freeware, Shareware dan Free Download Website', 'Free Download', 1, 'http://localhost/auracmsv3', 'admin@softponsel.com', 'mail', 465, '', '', '', 'ssl', 'Powered by Pantura Hosting Indonesia &amp;copy; www.panturahost.co.id ~ Web Hosting Murah, Web Development dan Web Apllication', 'AuraCMS', 'admin@auracms.org', 'auracms', 'duniamaya', 'admin@auracms.org,admin@softponsel.com', 'admin@auracms.org,admin@softponsel.com', 3600);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_topic`
--

DROP TABLE IF EXISTS `mod_topic`;
CREATE TABLE IF NOT EXISTS `mod_topic` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `topic` varchar(100) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `mod_topic`
--

INSERT INTO `mod_topic` (`id`, `topic`, `seftitle`) VALUES
(1, 'Berita Terkini', 'berita-terkini'),
(2, 'Olahraga', 'olahraga'),
(3, 'AuraCMS', 'auracms');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_user`
--

DROP TABLE IF EXISTS `mod_user`;
CREATE TABLE IF NOT EXISTS `mod_user` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` text NOT NULL,
  `skpd_id` int(2) NOT NULL,
  `daerah_id` int(2) NOT NULL,
  `email` varchar(30) NOT NULL,
  `name` varchar(30) NOT NULL,
  `city` varchar(100) NOT NULL,
  `active` int(2) NOT NULL DEFAULT '1',
  `level` enum('administrator','publisher') NOT NULL DEFAULT 'publisher',
  `timelogin` varchar(10) NOT NULL DEFAULT '3600',
  `images` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`,`email`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Struktur dari tabel `mod_usercounter`
--

DROP TABLE IF EXISTS `mod_usercounter`;
CREATE TABLE IF NOT EXISTS `mod_usercounter` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `counter` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `mod_usercounter`
--

INSERT INTO `mod_usercounter` (`id`, `ip`, `counter`, `hits`) VALUES
(1, '::1', 1, 105);

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_useronline`
--

DROP TABLE IF EXISTS `mod_useronline`;
CREATE TABLE IF NOT EXISTS `mod_useronline` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ip` varchar(15) NOT NULL DEFAULT '',
  `timestamp` varchar(15) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=105 ;

--
-- Dumping data untuk tabel `mod_useronline`
--

INSERT INTO `mod_useronline` (`id`, `ip`, `timestamp`) VALUES
(104, '::1', '1383459597');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_video`
--

DROP TABLE IF EXISTS `mod_video`;
CREATE TABLE IF NOT EXISTS `mod_video` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(225) NOT NULL,
  `description` text NOT NULL,
  `code` varchar(150) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `image` varchar(225) NOT NULL,
  `username` varchar(50) NOT NULL DEFAULT 'admin',
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_visitcounter`
--

DROP TABLE IF EXISTS `mod_visitcounter`;
CREATE TABLE IF NOT EXISTS `mod_visitcounter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tm` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL DEFAULT '0.0.0.0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data untuk tabel `mod_visitcounter`
--

INSERT INTO `mod_visitcounter` (`id`, `tm`, `ip`) VALUES
(1, 1382466151, '::1'),
(2, 1382467229, '::1'),
(3, 1382468720, '::1'),
(4, 1382470816, '::1'),
(5, 1382494865, '::1'),
(6, 1382495799, '::1'),
(7, 1382496907, '::1'),
(8, 1382498323, '::1'),
(9, 1382499899, '::1'),
(10, 1382500804, '::1'),
(11, 1382505774, '::1'),
(12, 1382575826, '::1'),
(13, 1382592000, '::1'),
(14, 1382592950, '::1'),
(15, 1382738860, '::1'),
(16, 1382740622, '::1'),
(17, 1382840263, '::1'),
(18, 1383267719, '::1'),
(19, 1383404358, '::1'),
(20, 1383459597, '::1');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_weblinks`
--

DROP TABLE IF EXISTS `mod_weblinks`;
CREATE TABLE IF NOT EXISTS `mod_weblinks` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `cat_id` int(2) NOT NULL,
  `url` varchar(225) NOT NULL,
  `author` varchar(50) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `published` int(2) NOT NULL,
  `hits` varchar(5) NOT NULL DEFAULT '0',
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seftitle` (`seftitle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `mod_weblinks`
--

INSERT INTO `mod_weblinks` (`id`, `title`, `description`, `cat_id`, `url`, `author`, `date`, `published`, `hits`, `seftitle`) VALUES
(2, 'AuraCMS', '<p>Website Resmi AuraCMS : Indonesia Content Management System</p>', 3, 'http://iwan.or.id', 'admin', '2012-07-02 16:55:42', 1, '2', 'auracms');

-- --------------------------------------------------------

--
-- Struktur dari tabel `mod_weblinks_cat`
--

DROP TABLE IF EXISTS `mod_weblinks_cat`;
CREATE TABLE IF NOT EXISTS `mod_weblinks_cat` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `seftitle` (`seftitle`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `mod_weblinks_cat`
--

INSERT INTO `mod_weblinks_cat` (`id`, `title`, `description`, `seftitle`) VALUES
(2, 'Personal', '<p>Berisi Website Personal</p>', 'personal'),
(3, 'AuraCMS', '<p>Berisi we</p>', 'auracms');

-- --------------------------------------------------------

--
-- Struktur dari tabel `optimize_gain`
--

DROP TABLE IF EXISTS `optimize_gain`;
CREATE TABLE IF NOT EXISTS `optimize_gain` (
  `gain` decimal(10,3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `optimize_gain`
--

INSERT INTO `optimize_gain` (`gain`) VALUES
(0.000),
(0.000),
(0.000),
(3.719),
(0.000),
(0.043),
(0.168),
(0.000),
(1.824),
(0.094),
(1.246),
(1.453),
(0.039),
(0.844),
(0.172),
(0.000),
(0.000),
(1.180),
(82944.406),
(122880.125),
(1320960.578);

-- --------------------------------------------------------

--
-- Struktur dari tabel `posted_ip`
--

DROP TABLE IF EXISTS `posted_ip`;
CREATE TABLE IF NOT EXISTS `posted_ip` (
  `id` bigint(21) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data untuk tabel `posted_ip`
--

INSERT INTO `posted_ip` (`id`, `file`, `ip`, `time`) VALUES
(25, 'contact', '::1', 1382593571),
(26, 'contact', '::1', 1382593805);

-- --------------------------------------------------------

--
-- Struktur dari tabel `stat_browse`
--

DROP TABLE IF EXISTS `stat_browse`;
CREATE TABLE IF NOT EXISTS `stat_browse` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `name` text NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data untuk tabel `stat_browse`
--

INSERT INTO `stat_browse` (`id`, `title`, `name`, `value`) VALUES
(1, 'Browser yang sering digunakan dalam mengakses halaman ini', 'Netscape#Opera#MSIE 4.0#MSIE 5.0#MSIE 6.0#Lynx#WebTV#Konqueror#bot#Other', '355685#5559#56#2663#14532#7#0#17#41569#11943'),
(2, 'Operating system', 'Windows#Mac#Linux#FreeBSD#SunOS#IRIX#BeOS#OS/2#AIX#Other', '90795#410#2063#9#48#0#0#2#0#338733'),
(3, 'Pengunjung berdasarkan hari', 'Minggu#Senin#Selasa#Rabu#Kamis#Jumat#Sabtu', '77#103#109#112#81#144#101'),
(4, 'Pengunjung berdasarkan bulan', 'Januari#Februari#Maret#April#Mei#Juni#Juli#Agustus#September#Oktober#November#Desember', '12193#454#1815#2221#1577#40517#147725#40962#56763#37561#41509#48777'),
(5, 'Pengunjung berdasarkan jam', '0:00 - 0:59#1:00 - 1:59#2:00 - 2:59#3:00 - 3:59#4:00 - 4:59#5:00 - 5:59#6:00 - 6:59#7:00 - 7:59#8:00 - 8:59#9:00 - 9:59#10:00 - 10:59#11:00 - 11:59#12:00 - 12:59#13:00 - 13:59#14:00 - 14:59#15:00 - 15:59#16:00 - 16:59#17:00 - 17:59#18:00 - 18:59#19:00 - 19:59#20:00 - 20:59#21:00 - 21:59#22:00 - 22:59#23:00 - 23:59', '17082#17599#17797#18512#18410#18780#18553#19837#20394#21568#20470#19069#17482#19718#17978#17131#15052#15052#15290#15081#18294#18050#19633#15246');

-- --------------------------------------------------------

--
-- Struktur dari tabel `tbl_kalender`
--

DROP TABLE IF EXISTS `tbl_kalender`;
CREATE TABLE IF NOT EXISTS `tbl_kalender` (
  `judul` varchar(255) NOT NULL DEFAULT '',
  `pengirim` varchar(50) NOT NULL,
  `isi` text NOT NULL,
  `waktu` varchar(50) NOT NULL,
  `waktu_mulai` date NOT NULL DEFAULT '0000-00-00',
  `waktu_akhir` date NOT NULL,
  `background` varchar(10) NOT NULL DEFAULT '#d1d1d1',
  `color` varchar(10) NOT NULL DEFAULT '',
  `id` int(12) NOT NULL AUTO_INCREMENT,
  `tanggal` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `gambar` varchar(225) NOT NULL,
  `lokasi` varchar(100) NOT NULL,
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

## 📑 Sommaire

- [Vulnérabilités étudiées](#-vulnérabilités-étudiées)
- [Récapitulatif des vulnérabilités](#-récapitulatif-des-vulnérabilités)
- [Audit détaillé](#-audit-détaillé)
- [ModSecurity](#️-modsecurity)
- [Tests WAF](#-tests-waf)
- [Comparaison](#️-comparaison)

## 🎯 Vulnérabilités étudiées

| ID  | Vulnérabilité              | Fichier(s)                | Catégorie OWASP                      |
|-----|----------------------------|---------------------------|--------------------------------------|
| V01 | Injection SQL (SQLi)       | `login.php`, `search.php` | A03:2021 – Injection                 |
| V02 | Cross-Site Scripting (XSS) | `search.php`              | A03:2021 – Injection                 |
| V03 | IDOR                       | `profile.php`             | A01:2021 – Broken Access Control     |
| V04 | File Upload                | `upload.php`              | A04:2021 – Insecure Design           |
| V05 | Local File Inclusion (LFI) | `index.php`               | A03:2021 – Injection                 |
| V06 | En-têtes HTTP manquants    | `config.php`              | A05:2021 – Security Misconfiguration |

---

## 📋 Récapitulatif des vulnérabilités

> Pour chaque faille : le code vulnérable et sa correction.

### V01 — Injection SQL · `Login.php`

| Élément             | Détail                                                                                                                                                                                                                                                    |
|---------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | SQLi (Données)                                                                                                                                                                                                                                            |
| **Cause**           | La concaténation des requêtes et l’utilisation des # et -- afin de passer les tests de sécurité                                                                                                                                                           |
| **Code vulnérable** | `$u = $_POST['username'];`<br>`$p = $_POST['password'];`<br>`$sql = "SELECT * FROM users WHERE username = '$u' AND password = '$p'";`<br>`$res = mysqli_query($conn, $sql);`                                                                              |
| **Correction**      | `$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE username = ? AND password = ?");`<br>`mysqli_stmt_bind_param($stmt, "ss", $_POST['username'], $_POST['password']);`<br>`mysqli_stmt_execute($stmt);`<br>`$res = mysqli_stmt_get_result($stmt);` |

### V01 — Injection SQL · `Search.php`

| Élément             | Détail                                                                                                                                                                                                                                                                                       |
|---------------------|----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | SQLi (Données)                                                                                                                                                                                                                                                                               |
| **Cause**           | Exécution de la requête par concaténation($q) sans vérifier si le texte est malveillant                                                                                                                                                                                                      |
| **Code vulnérable** | `$q = $_GET['q']; // Récupère ce que l'utilisateur tape dans la barre de recherche`<br>`$sql = "SELECT * FROM events WHERE title LIKE '%$q%' OR description LIKE '%$q%'";`<br>`$res = mysqli_query($conn, $sql);`                                                                            |
| **Correction**      | `$searchTerm = "%" . $_GET['q'] . "%";`<br>`$stmt = mysqli_prepare($conn, "SELECT * FROM events WHERE title LIKE ? OR description LIKE ?");`<br>`mysqli_stmt_bind_param($stmt, "ss", $searchTerm, $searchTerm);`<br>`mysqli_stmt_execute($stmt);`<br>`$res = mysqli_stmt_get_result($stmt);` |

### V02 — XSS · `Search.php`

| Élément             | Détail                                                                                                                                              |
|---------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | XSS (Sessions)                                                                                                                                      |
| **Cause**           | Le site n'est pas capable de faire la distinction entre du code ou du texte ce dernier, le navigateur exécute simplement le code plutôt que le test |
| **Code vulnérable** | `echo "<p>Résultats pour : " . $q . "</p>";`                                                                                                        |
| **Correction**      | `echo "<p>Résultats pour : " . htmlspecialchars($q, ENT_QUOTES, 'UTF-8') . "</p>";`                                                                 |

### V03 — IDOR · `Profile.php`

| Élément             | Détail                                                                                                                                                                                                                                                                                                                                                                                                                                              |
|---------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | IDOR (Données)                                                                                                                                                                                                                                                                                                                                                                                                                                      |
| **Cause**           | Modifier l'URL pour accéder a des fichiers ou autres informations sans vérifier les droits d’accès                                                                                                                                                                                                                                                                                                                                                  |
| **Code vulnérable** | `$id = $_GET['id'];`<br>`$sql = "SELECT * FROM users WHERE id = $id";`<br>`$res = mysqli_query($conn, $sql);`<br>`$user = mysqli_fetch_assoc($res);`                                                                                                                                                                                                                                                                                                |
| **Correction**      | `include('includes/config.php');`<br>`if (!isset($_SESSION['user_id'])) {`<br>`header("Location: login.php");`<br>`exit();`<br>`}`<br>`$id = $_SESSION['user_id']; $_SESSION['role'] !== 'admin') { ... }`<br>`$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE id = ?");`<br>`mysqli_stmt_bind_param($stmt, "i", $id);`<br>`mysqli_stmt_execute($stmt);`<br>`$res = mysqli_stmt_get_result($stmt);`<br>`$user = mysqli_fetch_assoc($res);` |

### V04 — File Upload · `Uploads.php`

| Élément             | Détail                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                          |
|---------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | File upload (site)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                              |
| **Cause**           | Lacceptation de n’importe quel fichier sans vérifier son script ou son extension (whitelist) en plus des permissions trop permissives (777)                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                     |
| **Code vulnérable** | —                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                               |
| **Correction**      | `<?php`<br>`if (isset($_FILES['file'])) {`<br>`$file_name = $_FILES['file']['name'];`<br>`$file_tmp = $_FILES['file']['tmp_name'];`<br>`$file_size = $_FILES['file']['size'];`<br>`$file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));`<br>`$allowed_extensions = ['jpg', 'jpeg', 'png', 'pdf'];`<br>`$max_size = 2 * 1024 * 1024;`<br>`if (in_array($file_ext, $allowed_extensions) && $file_size <= $max_size) {`<br>`$new_file_name = uniqid('secure_', true) . '.' . $file_ext;`<br>`$upload_destination = "uploads/" . $new_file_name;`<br>`if (move_uploaded_file($file_tmp, $upload_destination)) {`<br>`echo "Fichier envoyé et sécurisé avec succès.";`<br>`} else {`<br>`echo "Erreur lors de l'enregistrement du fichier.";`<br>`}`<br>`} else {`<br>`echo "Erreur : Type de fichier non autorisé ou fichier trop volumineux.";`<br>`}`<br>`}`<br>`?>` |

### V05 — LFI · `index.php`

| Élément             | Détail                                                                                                                                                                                                                                                                                                                                                                                                                            |
|---------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | LFI (fichiers)                                                                                                                                                                                                                                                                                                                                                                                                                    |
| **Cause**           | Manque de vigilance concernant les droits d’accès aux fichiers locales                                                                                                                                                                                                                                                                                                                                                            |
| **Code vulnérable** | `$page = $_GET['page'];`<br>`include($page);`                                                                                                                                                                                                                                                                                                                                                                                     |
| **Correction**      | `<?php`<br>`$allowed_pages = [`<br>`'home' => 'pages/home.php',`<br>`'about' => 'pages/about.php',`<br>`'contact' => 'pages/contact.php'`<br>`];`<br>`$page = $_GET['page'] ?? 'home';`<br>`// La page existe-t-elle dans la liste blanche ?`<br>`if (array_key_exists($page, $allowed_pages)) {`<br>`include($allowed_pages[$page]);`<br>`} else {`<br>`echo "Erreur : Page non trouvée ou accès non autorisé.";`<br>`}`<br>`?>` |

### V06 — Clickjacking · `Config.php`

| Élément             | Détail                                                                                                                                                        |
|---------------------|---------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **Catégorie**       | Clickjacking (serveur web)                                                                                                                                    |
| **Cause**           | L’intégration d’une balise HTML transparente depuis un site malveillant pour détourner l’utilisateur                                                          |
| **Code vulnérable** | L'absence totale de l'instruction header() pour définir les en-têtes de sécurité (comme X-Frame-Options, X-Content-Type-Options, ou Content-Security-Policy). |
| **Correction**      | `<?php`<br>`header("X-Frame-Options: DENY");`<br>`?>`                                                                                                         |

---

## 🔍 Audit détaillé

### V01 — SQL Injection (SQLi)

| **Identifiant**                        | V01                                                                                                                                         |
|----------------------------------------|---------------------------------------------------------------------------------------------------------------------------------------------|
| **Nom de la vulnérabilité**            | Injection SQL                                                                                                                               |
| **Catégorie (OWASP)**                  | A03:2021-Injection                                                                                                                          |
| **Fichier concerné**                   | login.php et/ou search.php                                                                                                                  |
| **Fonction concernée**                 | mysqli_query()                                                                                                                              |
| **Entrée contrôlée par l'utilisateur** | \$\_POST\['username'\], \$\_POST\['password'\] ou \$\_GET\['q'\]                                                                            |
| **Cause technique**                    | Concaténation directe de données non assainies directement dans la chaîne de la requête SQL                                                 |
| **Mécanisme**                          | Injection de charges utiles (payloads) contenant des caractères spéciaux de contrôle (' et \# ou --) pour modifier la logique de la requête |
| **Impact**                             | Contournement total de l'authentification et divulgation ou altération non autorisée des données de la base.                                |
| **Mesure corrective**                  | Utilisation de requêtes préparées avec liaison des paramètres                                                                               |
| **Vérification après correction**      | Le payload est strictement interprété comme une simple donnée, la requête échoue proprement ou s'exécute sans danger                        |

### V02 — Cross-Site Scripting (XSS réfléchi et stocké)

| **Identifiant**                        | V02                                                                                                                |
|----------------------------------------|--------------------------------------------------------------------------------------------------------------------|
| **Nom de la vulnérabilité**            | XSS                                                                                                                |
| **Catégorie (OWASP)**                  | A03:2021-Injection / XSS                                                                                           |
| **Fichier concerné**                   | search.php                                                                                                         |
| **Fonction concernée**                 | Echo                                                                                                               |
| **Entrée contrôlée par l'utilisateur** | \$\_GET\['q'\]                                                                                                     |
| **Cause technique**                    | Absence d'encodage de sortie avant de réafficher la saisie utilisateur dans le code HTML                           |
| **Mécanisme**                          | Injection de balises de script actives transmises directement dans le code de la page renvoyée par le serveur.     |
| **Impact**                             | Vol de cookies de session, détournement d'interface, exécution de code arbitraire dans le navigateur de la victime |
| **Mesure corrective**                  | Utilisation systématique de la fonction htmlspecialchars() lors de l'affichage de données utilisateur              |
| **Vérification après correction**      | Le script s'affiche sous forme de texte brut inoffensif dans la page sans jamais s'exécuter.                       |

### V03 — IDOR — Insecure Direct Object Reference

| **Identifiant**                        | V03                                                                                                                                  |
|----------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------|
| **Nom de la vulnérabilité**            | IDOR                                                                                                                                 |
| **Catégorie (OWASP)**                  | A01:2021-Broken Access Control                                                                                                       |
| **Fichier concerné**                   | profile.php                                                                                                                          |
| **Fonction concernée**                 | mysqli_query()                                                                                                                       |
| **Entrée contrôlée par l'utilisateur** | \$\_GET\['id'\]                                                                                                                      |
| **Cause technique**                    | Absence de contrôle d'autorisation côté serveur pour valider si l'utilisateur connecté a le droit d'accéder à la ressource demandée. |
| **Mécanisme**                          | Modification séquentielle de l'identifiant numérique dans l'URL pour cibler les profils d'autres utilisateurs.                       |
| **Impact**                             | Divulgation non autorisée d'informations personnelles et sensibles (e-mails, rôles).                                                 |
| **Mesure corrective**                  | Implémentation d'un contrôle d'autorisation strict comparant l'ID demandé à la session active de l'utilisateur                       |
| **Vérification après correction**      | Refus d'accès ou redirection automatique si l'utilisateur tente de consulter un profil qui ne lui appartient pas                     |


### V04 — File Upload

| **Identifiant**                        | V04                                                                                                                                             |
|----------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------|
| **Nom de la vulnérabilité**            | File upload                                                                                                                                     |
| **Catégorie (OWASP)**                  | A04:2021-Insecure Design / A06:2021                                                                                                             |
| **Fichier concerné**                   | upload.php                                                                                                                                      |
| **Fonction concernée**                 | move_uploaded_file()                                                                                                                            |
| **Entrée contrôlée par l'utilisateur** | \$\_FILES\['file'\]                                                                                                                             |
| **Cause technique**                    | Absence de liste blanche d'extensions, absence de validation du type MIME et conservation du nom d'origine                                      |
| **Mécanisme**                          | Envoi d'un script exécutable (ex: .php) stocké directement dans un répertoire accessible par le serveur web.                                    |
| **Impact**                             | Exécution de code à distance (RCE) et compromission totale de l'infrastructure serveur                                                          |
| **Mesure corrective**                  | Mise en place d'une liste blanche d'extensions, renommage aléatoire sécurisé du fichier et interdiction d'exécution de scripts dans le dossier. |
| **Vérification après correction**      | Rejet immédiat des fichiers non autorisés et impossibilité pour le serveur d'exécuter les fichiers téléversés.                                  |


### V05 — Local File Inclusion (LFI)

| **Identifiant**                        | V05                                                                                                                           |
|----------------------------------------|-------------------------------------------------------------------------------------------------------------------------------|
| **Nom de la vulnérabilité**            | LFI                                                                                                                           |
| **Catégorie (OWASP)**                  | A03:2021-Injection                                                                                                            |
| **Fichier concerné**                   | index.php                                                                                                                     |
| **Fonction concernée**                 | include()                                                                                                                     |
| **Entrée contrôlée par l'utilisateur** | \$\_GET\['page'\]                                                                                                             |
| **Cause technique**                    | Utilisation directe de données utilisateur non filtrées dans une fonction d'inclusion de fichiers PHP.                        |
| **Mécanisme**                          | Utilisation de caractères de remontée de répertoires (../) pour s'affranchir du dossier racine et cibler des fichiers système |
| **Impact**                             | Lecture de fichiers sensibles du système d'exploitation du serveur (ex: /etc/passwd).                                         |
| **Mesure corrective**                  | Utilisation d'une liste blanche (Whitelist) stricte des pages autorisées ou d'un mapping de correspondance sécurisé.          |
| **Vérification après correction**      | Blocage de l'inclusion dès que la valeur transmise ne correspond pas strictement aux clés de la liste blanche.                |

### V06 — En-têtes de sécurité HTTP

| **Identifiant**                        | V06                                                                                                                          |
|----------------------------------------|------------------------------------------------------------------------------------------------------------------------------|
| **Nom de la vulnérabilité**            | Absence d'en-têtes de sécurité HTTP                                                                                          |
| **Catégorie (OWASP)**                  | A05:2021-Security Misconfiguration                                                                                           |
| **Fichier concerné**                   | config.php                                                                                                                   |
| **Fonction concernée**                 | Configuration des réponses HTTP                                                                                              |
| **Entrée contrôlée par l'utilisateur** | N/A (Erreur de configuration / conception)                                                                                   |
| **Cause technique**                    | Absence de définition des en-têtes de protection (comme X-Frame-Options ou CSP) dans les réponses du serveur.                |
| **Mécanisme**                          | Exploitation du manque de restrictions pour intégrer l'application dans une balise iframe externe.                           |
| **Impact**                             | Exposition au Clickjacking (détournement de clics) et au MIME-sniffing.                                                      |
| **Mesure corrective**                  | Ajout des en-têtes de sécurité HTTP via la configuration du serveur (ex: Apache) ou au début du fichier config.php (header() |
| **Vérification après correction**      | Présence effective des en-têtes de sécurité dans les réponses HTTP du serveur.                                               |

---

## 🛡️ ModSecurity

| **Élément**          | **Valeur**                                                                             |
|----------------------|----------------------------------------------------------------------------------------|
| Version              | ModSecurity v2.9 / v3                                                                  |
| Hyperviseur          | Docker                                                                                 |
| CPU / RAM / Disque   | Ressources allouées au conteneur (ex: 1 vCPU / 1 Go RAM / Stockage conteneur standard) |
| Mode de déploiement  | WAF intégré / Embarqué                                                                 |
| URL d'administration | /var/log/apache2/modsec_audit.log                                                      |

---

## 🧪 Tests WAF

### 🔴 Scénario A — Application vulnérable sans WAF

*Client → Application vulnérable*

| **Test** | **Requête / Payload**                                | **Résultat**                                                                                                              |
|----------|------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------------|
| SQLi     | admin' \#                                            | Contournement total de l'authentification, connexion réussie en tant qu'administrateur sans connaître le mot de passe.    |
| XSS      | \<script\>alert('XSS')\</script\>                    | Exécution du code JavaScript dans le navigateur de l'utilisateur (affichage de la boîte de dialogue).                     |
| IDOR     | profile.php?id=1                                     | Accès non autorisé aux données confidentielles du profil administrateur en modifiant simplement l'identifiant dans l'URL. |
| Upload   | test.php (contenant \<?php echo "Shell actif !"; ?\> | Le fichier exécutable est accepté, stocké sur le serveur et son code PHP s'exécute lors de son ouverture.                 |
| LFI      | index.php?page=../../../../etc/passwd                | Lecture et affichage direct du contenu du fichier système sensible /etc/passwd sur la page web.                           |

### 🟠 Scénario B — Application vulnérable derrière le WAF

*Client → FortiWeb → Application vulnérable*

| **Test** | **Requête autorisée / bloquée**       | **Protection déclenchée**                                         | **Résultat**                                                                                                                                                    |
|----------|---------------------------------------|-------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------------------------|
| SQLi     | admin' \#                             | Protection contre les Injections SQL (Règles OWASP CRS)           | Interception de la charge utile par le WAF avant qu'elle n'atteigne l'application.                                                                              |
| XSS      | \<script\>alert('XSS')\</script\>     | Protection contre le Cross-Site Scripting                         | Détection de la balise de script active et rejet de la requête                                                                                                  |
| IDOR     | profile.php?id=1                      | Contrôle d'accès (Non bloqué par signature WAF par défaut)        | Les failles IDOR relèvent de la logique applicative ; un WAF basé sur les signatures ne bloque pas les paramètres numériques sans règle contextuelle spécifique |
| Upload   | test.php                              | Protection contre le téléversement de fichiers non sécurisés      | Refus du fichier script exécutable par le filtre du WAF.                                                                                                        |
| LFI      | index.php?page=../../../../etc/passwd | Protection contre l'inclusion de fichiers locaux (Path Traversal) | Détection des caractères de remontée de répertoire (../) et blocage de l'accès.                                                                                 |

### 🟢 Scénario C — Application sécurisée derrière Le WAF

*Client → FortiWeb → Application sécurisée*

| **Test** | **Requête autorisée / bloquée**            | **Protection déclenchée**                                                     | **Résultat**                                                                                                                                                       |
|----------|--------------------------------------------|-------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| SQLi     | Bloquée                                    | Protection contre les Injections SQL (WAF) + Requêtes préparées               | La requête malveillante est interceptée et bloquée en amont par le WAF (Erreur 403), ou neutralisée à la source par le code.                                       |
| XSS      | Bloquée                                    | Protection XSS (WAF) + Encodage de sortie htmlspecialchars()                  | Bloqué par le WAF avant d'atteindre l'application, et converti en texte inoffensif par le code si transmis. Zéro exécution.                                        |
| IDOR     | Autorisée par le WAF / Bloquée par le code | Contrôle d'accès et vérification des sessions                                 | Le WAF laisse passer la requête (paramètre syntaxiquement valide), mais le code sécurisé bloque l'accès non autorisé grâce à la vérification des droits de session |
| Upload   | Bloquée                                    | Filtrage des types de fichiers (WAF) + Liste blanche et nommage unique (Code) | Le téléversement de scripts exécutables non autorisés est stoppé net par le WAF et rejeté par le code source.                                                      |
| LFI      | Bloquée                                    | Protection contre le Path Traversal (WAF) + Liste blanche des pages (Code)    | Les tentatives de remontée de répertoires (../) sont détectées et bloquées par le WAF, et strictement interdites par le code.                                      |

### 📜 Analyse des logs du WAF

| **Date / heure**    | **IP source** | **Destination** | **URL**             | **Méthode** | **Attaque détectée** | **Action**          | **Règle / Protection** | **Résultat**                                    |
|---------------------|---------------|-----------------|---------------------|-------------|----------------------|---------------------|------------------------|-------------------------------------------------|
| 22/10/2026 20:07:06 | 172.19.0.1    | 172.19.0.3:80   | /login.php          | POST        | Injection SQL        | Journalisé / Traité | ModSecurity            | Requête enregistrée et traitée par le serveur   |
| 22/10/2026 20:07:22 | 172.19.0.1    | 172.19.0.3:80   | /search.php         | GET         | XSS                  | Journalisé / Traité | ModSecurity            | Requête enregistrée et transitée vers le code   |
| 22/10/2026 19:51:53 | 172.19.0.1    | 172.19.0.3:80   | /index.php?page=... | GET         | LFI                  | Journalisé / Traité | ModSecurity            | Requête enregistrée et analysée par le moteur   |
| 22/10/2026 20:11:11 | 172.19.0.1    | 172.19.0.3:80   | /upload.php         | POST        | File upload          | Journalisé / Traité | ModSecurity            | Requête d'envoi de fichier tracée dans les flux |
| 22/10/202619:18:08  | 172.19.0.1    | 172.19.0.3:80   | /profile.php        | GET         | IDOR                 | Journalisé / Traité | ModSecurity            |                                                 |

---

## ⚖️ Comparaison

### Les trois configurations

| **Configuration**                | **Résultat attendu**                                                                                        | **Résultat observé**                                                                                                                             | **Différences / Explications**                                                                                                                |
|----------------------------------|-------------------------------------------------------------------------------------------------------------|--------------------------------------------------------------------------------------------------------------------------------------------------|-----------------------------------------------------------------------------------------------------------------------------------------------|
| Application vulnérable, sans WAF | Succès de toutes les attaques (SQLi, XSS, IDOR, LFI, Upload non sécurisé)                                   | Les charges malveillantes (payloads) s'exécutent sans encombre ; les données confidentielles et les fichiers sensibles sont accessibles          | Absence totale de filtrage et de contrôle d'accès. Le code fait une confiance aveugle aux entrées de l'utilisateur                            |
| Application vulnérable + WAF     | Interception et blocage des requêtes malveillantes courantes grâce aux règles du WAF (ModSecurity / CRS)    | Les tentatives d'attaques par signature (SQLi, XSS, LFI) sont journalisées et bloquées ou filtrées en amont.                                     | Le WAF protège le périmètre externe, mais le code source sous-jacent reste intrinsèquement vulnérable si une attaque contourne les signatures |
| Application sécurisée + WAF      | Protection maximale de l'application grâce au principe de défense en profondeur (code corrigé + WAF actif). | Le trafic légitime passe sans erreur, tandis que les anomalies et attaques sont bloquées à la fois par le WAF et neutralisées par le code source | Combinaison idéale : le code sécurisé (secure-version) neutralise les failles logiques (comme l'IDOR) et le WAF bloque les attaques réseau    |

### Détail par vulnérabilité

| **Vulnérabilité** | **Vulnérable sans WAF**                                                                                                      | **Vulnérable + WAF**                                                                                                          | **Sécurisée + WAF**                                                                                                 |
|-------------------|------------------------------------------------------------------------------------------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------|---------------------------------------------------------------------------------------------------------------------|
| SQL Injection     | Contournement de l'authentification et extraction de données sensibles via l'injection de charges malveillantes (admin' \#). | Le WAF (ModSecurity / OWASP CRS) identifie la signature de l'injection SQL et bloque la requête.                              | Utilisation de requêtes préparées (prepared statements) dans le code source sécurisé, combinées au filtrage du WAF. |
| XSS               | Exécution du code JavaScript injecté (\<script\>alert('XSS')\</script\>) dans le navigateur de la victime                    | Le WAF intercepte les balises de script et bloque la tentative de Cross-Site Scripting.                                       | Échappement systématique des données affichées (htmlspecialchars) dans le code + protection du WAF.                 |
| IDOR              | Accès non autorisé aux données d'un autre utilisateur en modifiant simplement l'identifiant dans l'URL (?id=1).              | Le WAF ne bloque généralement pas cette faille car c'est une erreur logique applicative (la requête HTTP a l'air légitime).   | Vérification stricte de la session active (\$\_SESSION) par rapport à l'objet demandé dans le code corrigé          |
| File Upload       | Téléversement d'un fichier non sécurisé ou malveillant (ex: script PHP) sur le serveur sans restriction.                     | Le WAF peut bloquer certains contenus suspects, mais un fichier malveillant peut parfois passer s'il contourne les signatures | Validation stricte des extensions, vérification du type MIME, et renommage sécurisé des fichiers côté code.         |
| LFI               | Lecture de fichiers sensibles du système hôte (/etc/passwd) via une traversée de répertoires (../).                          | Le WAF détecte les séquences de traversée de répertoire (../) et bloque instantanément la requête                             | Implémentation d'une liste blanche stricte des fichiers autorisés ou suppression de l'inclusion dynamique.          |

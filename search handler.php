<?php
// URL site
$baseUrl = 'http://localhost:8888/Projet_Piscine/';

$search = $_POST['search'] ?? '';

// Connexion
$db_handle = mysqli_connect('localhost', 'root', 'root', 'Sportify', 8888);
if (!$db_handle) {
    die('Erreur de connexion: ' . mysqli_connect_error());
}

mysqli_set_charset($db_handle, "utf8");

// requete sql

$query = "SELECT nom, prenom, specialite, photo, mail, CV, bureau, calendrier 
          FROM Specialiste 
          WHERE nom LIKE ? OR prenom LIKE ? OR specialite LIKE ? OR bureau LIKE ?";
$stmt = mysqli_prepare($db_handle, $query);

$searchTerm = "%" . $search . "%";
mysqli_stmt_bind_param($stmt, 'ssss', $searchTerm, $searchTerm, $searchTerm, $searchTerm);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Resultats

if ($result && mysqli_num_rows($result) > 0) {
    echo "<div class='result-container'>";
    while ($row = mysqli_fetch_assoc($result)) {
        $imagePath = $baseUrl . $row['photo'];  // Construit le chemin absolu de l'image
        echo "<div class='result-item'>";
        echo "<img src='{$imagePath}' alt='Photo de " . htmlspecialchars($row['prenom']) . " " . htmlspecialchars($row['nom']) . "' style='width:100%; max-width:300px; height:auto; display:block; margin:auto;'>";
        echo "<div class='info'>";
        echo "<p><strong>Nom:</strong> " . htmlspecialchars($row['nom']) . "</p>";
        echo "<p><strong>Prénom:</strong> " . htmlspecialchars($row['prenom']) . "</p>";
        echo "<p><strong>Spécialité:</strong> " . htmlspecialchars($row['specialite']) . "</p>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['mail']) . "</p>";
        echo "<p><strong>CV:</strong> <a href='{$row['CV']}' target='_blank'>Ouvrir CV</a></p>";
        echo "<p><strong>Bureau:</strong> " . htmlspecialchars($row['bureau']) . "</p>";
        echo "<p><strong>Calendrier:</strong> " . htmlspecialchars($row['calendrier']) . "</p>";
        echo "</div>";
        echo "</div>";
    }
    echo "</div>";
} else {
    echo "Aucun résultat trouvé. Veuillez vérifier votre saisie.";
}

mysqli_stmt_close($stmt);
mysqli_close($db_handle);
?>

<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require 'vendor/autoload.php';
ob_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifie si le fichier a été téléversé avec succès
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fichierTexteTemp = $_FILES['file']['tmp_name'];

        // Vérification du type MIME du fichier
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $fichierTexteTemp);
        finfo_close($finfo);

        if ($mime_type !== 'text/plain') {
            echo "Fichier invalide
            Veuillez choisir un fichier texte.";
            exit();
        }

        // Création d'un tableau pour stocker les données du fichier texte
        $dataArray = [];

        // Lecture du fichier texte
        $lines = file($fichierTexteTemp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
           
            // Ajoutez ici votre logique pour extraire les données du fichier texte
            // ...
           // while (($line = fgets($file)) !== false) {
           
                if (strlen($line) < 12) {
                    continue;
                }
                
                $codeLieuEtPlaqueParts = str_split(substr($line, 0, 12), 6);
                $codeLieu = "";
                $plaque = "";
        
                if (count($codeLieuEtPlaqueParts) > 1) {
                    $codeLieu = $codeLieuEtPlaqueParts[0];
                    $plaque = $codeLieuEtPlaqueParts[1];
                }
        
                $reste = substr($line, 12);
        
                $dateCarburantEtKilometrage = "";
                $date = "";
                $carburant = "";
                $kilometrage = "";
                $parc = "";
                $dateR = null;
                $positionDateCarburant = -1;
            
                $reste = substr($line, 12); // creer une nouvelle chaine de caractere
            
                $dateCarburantEtKilometrage = "";
                $date = "";
                $carburant = "";
                $kilometrage = "";
                $parc = "";
                $dateR = null;
                $positionDateCarburant = -1;
            
                for ($i = 0; $i < strlen($reste); $i++) { //permet de parcourir chaque caractere de la chaine et s arrete des il y carac nume
                    if (is_numeric($reste[$i])) {
                        $positionDateCarburant = $i;
                        break;
                    }
                }
            
                if ($positionDateCarburant > -1) {  //si vrai un carac num trouve dans res
                    $dateCarburantEtKilometrage = substr($reste, $positionDateCarburant);
                }
            
                $dateCarburantEtKilometrage = preg_replace('/\s+/', ' ', $dateCarburantEtKilometrage); // remplace double space en 1
                $dateCarburantEtKilometrageParts = explode(" ", $dateCarburantEtKilometrage);
            
                if (count($dateCarburantEtKilometrageParts) > 1) {
                    $dateCarburant = $dateCarburantEtKilometrageParts[0];
            
                    if (strlen($dateCarburant) == 17) {
                        $date = substr($dateCarburant, 0, 10);
                        $carburantRaw = substr($dateCarburant, 10, strlen($dateCarburant) - 1);
                
                        // Retirez la lettre "G" de la valeur de carburant
                        $carburant = floatval(str_replace('G', '', $carburantRaw));
                    }
            
                    $stringParc = $dateCarburantEtKilometrageParts[1];
            
                    if (strlen($stringParc) == 8) {
                        $kilometrage = substr($stringParc, 4);
                    }
                }
            
                if (is_numeric($codeLieu)) {
                    if (intval($codeLieu) == 1) {
                        $codeLieu = "1";
                    } elseif (intval($codeLieu) == 2) {
                        $codeLieu = "2";
                    }
                }
            
                if (is_numeric($plaque)) { // verif si numeric verif
                    $plaqueInt = intval($plaque);
            
                    //permet determiner categorie v en fonction plaque immatriculation
                    if ($plaque == "ATC") {
                        $parc = "0004";
                    } elseif (($plaqueInt >= 2 && $plaqueInt <= 1109) || strlen($plaque) == 5 || $plaqueInt == 1512 || $plaqueInt == 1501) {
                        $parc = "Utilitaire/V/";
                    } elseif ($plaqueInt >= 1339 && $plaqueInt <= 4004 || $plaqueInt == 1009) {
                        $parc = "Tata/Mini Bus/";
                    } elseif ($plaqueInt >= 7701 && $plaqueInt <= 7806) {
                        $parc = "Tata/Bus GP/";
                    } elseif ($plaqueInt >= 7901 && $plaqueInt <= 7915) {
                        $parc = "DTII Ashok/BUS/";
                    } elseif ($plaqueInt >= 7916 && $plaqueInt <= 7950) {
                        $parc = "Tata/Bus GP/";
                    } elseif ($plaqueInt >= 9000 && $plaqueInt <= 9399) {
                        $parc = "DTU Ashok/Bus/";
                    } elseif ($plaqueInt >= 9501 && $plaqueInt <= 9610) {
                        $parc = "DTU Ashok/Falcon/";
                    } elseif ($plaqueInt >= 9701 && $plaqueInt <= 9763) {
                        $parc = "DTII Ashok/BUS/";
                    } elseif ($plaqueInt >= 9801 && $plaqueInt <= 9815) {
                        $parc = "DTII Ashok/BUS/";
                    } elseif ($plaqueInt >= 6700 && $plaqueInt <= 6800) {
                        $parc = "DTII IVECO/BUS/";
                    } elseif ($plaqueInt >= 6000 && $plaqueInt <= 6600) {
                        $parc = "DTU IVECO/BUS/";
                    }
                   
                   
                }
                try {
          
        $dateR = DateTime::createFromFormat("dmyHi", $date);
        
        // Ajout de déclarations de débogage
        //var_dump($codeLieu, $plaque, $dateR, $carburant, $kilometrage, $parc);
        
        if (is_numeric($carburant)) {
            $carburant = number_format($carburant, 1, ',', '0');        }
        
        if (is_numeric($kilometrage)) {
            $kilometrage = floatval($kilometrage) / 10;
        }
      

            // Exemple: Ajout de données dans le tableau

            $dataArray[] = [$codeLieu, $plaque, ($dateR instanceof DateTime) ? $dateR->format("d/m/Y H:i") : 'Date non valide', $carburant, $kilometrage, $parc . $plaqueInt];
        } catch (Exception $e) {
            // En cas d'erreur, enregistrez l'erreur dans une cellule du fichier Excel
            $dataArray[] = ['Erreur: ' . $e->getMessage()];
        }
        }

        // Création du fichier Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray($dataArray);

        // Sauvegarde du fichier Excel
        $excelWriter = new Xlsx($spreadsheet);
        $excelFileName = 'resultat ' . date('Y-m-d ') . '.xlsx'; 
        $excelWriter->save($excelFileName);

        // Envoi du fichier Excel au navigateur pour le téléchargement
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $excelFileName . '"');
        header('Cache-Control: max-age=0');
        $excelWriter->save('php://output');

        // Suppression du fichier Excel temporaire
        unlink($excelFileName);
        unlink($fichierTexteTemp);
        // Terminaison du script
        exit();
    } else {
        echo "Erreur lors du téléversement du fichier.";
    }
}
ob_end_clean();
?>
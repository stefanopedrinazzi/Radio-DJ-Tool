# RadioDJ Library Assistant

  RadioDJ Library Assistant è un tool che implementa delle funzionalità aggiuntive per una migliore gestione della libreria e della programmazione musicale di RadioDJ.<br /><br />

## Prerequisiti

  Per l'utilizzo di RadioDJ Library Assistant è necessario aver installato sul proprio pc un web server e un RDBMS.
  
  La progettazione è stata effettuata in php v 7.1.10, utilizzando XAMPP 7.1.11 che utilizza come web server Apache v. 2.4.29 e come RDBMS MariaDB v. 10.1.28.<br /><br />

## Installazione

  Effettuata l'installazione di <strong>XAMPP</strong>, dopo aver avviato MySQL e Apache è necessario importare il database rdj_library_assistant.sql .
  Per importare il database bisogna accedere al link http://localhost/phpmyadmin/index.php , creare un nuovo db, assegnandogli il nome <strong>rdj_library_assistant</strong> e importando il file <strong>rdj_library_assistant.sql</strong> presente nella cartella <strong>RadioDJ-Library-Assistant</strong>.<br />

Copiare la cartella <strong>RadioDJ-Library-Assistant</strong> in <strong>htdocs</strong>, presente nella cartella <strong>XAMPP</strong> creata dall'installazione. <br /><br />


Non resta che accedere a http://localhost/RadioDJ-Library-Assistant/index.php per iniziare ad utilizzare il tool.
  
Durante il primo accesso non sarà possibile utilizzare le varie funzionalità se non si inseriscono prima le credenziali di accesso ai database.
  
 Per fare questo è necessario entrare nelle <strong>impostazioni</strong> e <strong>inserire</strong>:
 
 -Nome del database di radioDJ;<br />
 -Nome dell'host;<br />
 -Username database di radioDJ;<br />
 -Password database di radioDJ;<br />
 -Username database di radioDJ Library Assistant;<br />
 -Password database di radioDJ Library Assistant;<br />
 -Root directory*;<br />
 -Lingua.<br />
  
 *cartella nella quale il tool "consolida categorie" copierà i file.
 
 Una volta inserite le credenziali per l'accesso ai database, i tool saranno attivati e pronti all'uso.

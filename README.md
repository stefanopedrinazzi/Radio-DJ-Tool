# RadioDJ Library Assistant

  RadioDJ Library Assistant è un set di utility che semplificano la gestione della libreria e della programmazione musicale di RadioDJ.  <br /><strong>Non</strong> sostituisce il <em>tracks manager</em> e <em>events builder</em> di RadioDJ, ma implementa delle funzionalità aggiuntive che permettono una gestione semplificata della libreria e di alcune attività.
  <br /><br />
  
## Attenzione
Non ci assumiamo nessuna responsabilità sull'uso di questo sistema.

Prima di effettuare qualsiasi operazione si consiglia di effettuare una copia di <strong>backup</strong> del database e dei file utilizzati da RadioDJ.<br /><br />

## Prerequisiti

  Per l'utilizzo di RadioDJ Library Assistant è necessario aver installato sul proprio pc il software RadioDJ V.2.0.x, un web server e un RDBMS.
  
  La progettazione è stata effettuata utilizzando Php v 7.1.10, XAMPP 7.1.11 (Apache v. 2.4.29) e MariaDB (v. 10.1.28) come RDBMS.<br /><br />

## Installazione

  Effettuata l'installazione di <strong>XAMPP</strong>, dopo aver avviato MySQL e Apache è necessario importare il database rdj_library_assistant.sql .
  Per importare il database bisogna accedere a [phpmyadmin](http://localhost/phpmyadmin/index.php), creare un nuovo db, assegnandogli il nome <strong>rdj_library_assistant</strong> e importando il file <strong>rdj_library_assistant.sql</strong> presente nella cartella <strong>RadioDJ-Library-Assistant</strong>.<br />

Copiare la cartella <strong>RadioDJ-Library-Assistant</strong> in <strong>htdocs</strong>, presente nella cartella <strong>XAMPP</strong> creata dall'installazione. <br /><br />


Non resta che accedere a Radiodj Library Assistant http://localhost/RadioDJ-Library-Assistant/index.php per iniziare ad utilizzare il tool.
  
Per utilizzare le funzionalità di Radiodj Library Assistant é necessario inserire le credenziali di accesso ai database nel menù <strong>impostazioni</strong> dove sarà possibile definire:
 
 -Nome del database di radioDJ;<br />
 -Nome dell'host;<br />
 -Username database di radioDJ;<br />
 -Password database di radioDJ;<br />
 -Username database di radioDJ Library Assistant;<br />
 -Password database di radioDJ Library Assistant;<br />
 -Root directory*;<br />
 -Lingua.<br />
  
 *cartella nella quale il tool "consolida categorie" sposterà i file.
 
Inserite le credenziali corrette per l'accesso ai database, i tool saranno attivati e pronti all'uso.
  
 ## Autori
 
 - Stefano Pedrinazzi
 - Paolo Camozzi
 <br />
 
 ## Licenza
 
Questo progetto è concesso in licenza con la licenza MIT - per i dettagli vedere il file [LICENSE.md](https://github.com/stefanopedrinazzi/RadioDJ-Library-Assistant/blob/master/LICENSE.md) <br /><br />
 
  ## Credits
  
  - [RadioDJ](http://www.radiodj.ro/)
  - [Semantic UI](https://semantic-ui.com/)
  - [DataTables](https://datatables.net/)
  - [Charts.js](http://www.chartjs.org/)
  - [Semantic-UI-Calendar](https://github.com/mdehoog/Semantic-UI-Calendar)
 

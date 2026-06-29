<?php

class JavadocBuilder {
    private string $baseDir;
    private string $srcDir;
    private string $docsDir;
    private string $customDocsDir;
    private string $javadocCmd;
    private string $projectName;
    private bool $enableJavadoc;

    public function __construct(string $folderName, string $javadocCmd, string $projectName = 'Database Schema', bool $enableJavadoc = false) {
        $this->baseDir = __DIR__ . "/../storage/" . $folderName;
        $this->srcDir = $this->baseDir . "/src";
        $this->docsDir = $this->baseDir . "/docs";
        $this->customDocsDir = $this->baseDir . "/custom_docs";
        $this->javadocCmd = trim($javadocCmd);
        $this->projectName = $projectName;
        $this->enableJavadoc = $enableJavadoc;
    }

    public function build(array $tables): array {
        if (!mkdir($this->srcDir, 0777, true) || !mkdir($this->customDocsDir, 0777, true)) {
            return ['success' => false, 'message' => 'Error creating directories.'];
        }

        $this->generateJavaFiles($tables);
        
        if ($this->enableJavadoc) {
            $output = $this->runJavadoc();
        }

        $this->generateCustomDocs($tables);

        $result = [
            'success' => true, 
            'docs_url' => "storage/" . basename($this->baseDir) . "/custom_docs/index.html"
        ];

        if ($this->enableJavadoc) {
            $result['javadoc_url'] = "storage/" . basename($this->baseDir) . "/docs/index.html";
        }

        return $result;
    }

    private function generateJavaFiles(array $tables): void {
        foreach ($tables as $table) {
            $className = ucfirst(htmlspecialchars($table['name']));
            $javaCode = "public class $className {\n    private $className() {}\n";
            if (isset($table['columns'])) {
                foreach ($table['columns'] as $col) {
                    $colName = preg_replace('/[^a-zA-Z0-9_]/', '', $col['name']); 
                    if(!empty($colName)) $javaCode .= "    public Object $colName;\n";
                }
            }
            $javaCode .= "}\n";
            file_put_contents($this->srcDir . "/$className.java", $javaCode);
        }
    }

    private function runJavadoc(): string {
        $cmd = $this->javadocCmd . ' -d ' . escapeshellarg($this->docsDir) . ' -encoding UTF-8 ' . escapeshellarg($this->srcDir) . '/*.java 2>&1';
        return (string) shell_exec($cmd);
    }

    private function generateCustomDocs(array $tables): void {
        $cssContent = file_get_contents(__DIR__ . '/../css/db-style.css');
        file_put_contents($this->customDocsDir . '/style.css', $cssContent);

        if (!is_dir($this->customDocsDir . '/images')) {
            mkdir($this->customDocsDir . '/images', 0777, true);
        }
        $sourceImage = __DIR__ . '/../images/db-symbol.png';
        if (file_exists($sourceImage)) {
            copy($sourceImage, $this->customDocsDir . '/images/db-symbol.png');
        }

        $navHtml = $this->buildSidebarNav($tables);

        $indexContent = "<h1 class='page-title'>" . htmlspecialchars($this->projectName) . "</h1>";
        $indexContent .= "<p class='page-desc'>All tables</p>";
        $indexContent .= $this->buildTableList($tables);
        $indexHtml = $this->buildHtmlWrapper('Schema Overview', $indexContent, $navHtml, 'index.html');
        file_put_contents($this->customDocsDir . '/index.html', $indexHtml);

        foreach ($tables as $table) {
            $tableName = ucfirst(htmlspecialchars($table['name']));
            $pageContent = $this->buildTablePageContent($table);
            $html = $this->buildHtmlWrapper("$tableName", $pageContent, $navHtml, "$tableName.html");
            file_put_contents($this->customDocsDir . "/$tableName.html", $html);
        }
    }

    private function buildSidebarNav(array $tables): string {
        $html = "<a href='index.html' class='nav-link' data-page='index.html'>Overview</a>";
        foreach ($tables as $table) {
            $name = ucfirst(htmlspecialchars($table['name']));
            $html .= "<a href='$name.html' class='nav-link' data-page='$name.html'>$name</a>";
        }
        return $html;
    }

    private function buildHtmlWrapper(string $title, string $content, string $navHtml, string $currentPage): string {
        return "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>$title &middot; Database Documentation</title>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
    <aside class='sidebar'>
        <a href='index.html' class='sidebar-brand'>
            <img src='images/db-symbol.png' alt='Symbol representing Database' class='db-symbol'>
            Database Documentation
        </a>
        <div class='nav-group'>TABLES</div>
        <nav class='nav-links'>
            $navHtml
        </nav>
    </aside>
    <main class='content-area'>
        <div class='container'>
            $content
        </div>
    </main>
    <script>
        document.querySelectorAll('.nav-link').forEach(link => {
            if (link.getAttribute('data-page') === '$currentPage') {
                link.classList.add('active');
            }
        });
    </script>
</body>
</html>";
    }

    private function buildTableList(array $tables): string {
        $html = "<div class='table-grid'>";
        foreach ($tables as $table) {
            $name = ucfirst(htmlspecialchars($table['name']));
            $desc = htmlspecialchars($table['description'] ?? '');
            $html .= "<a href='$name.html' class='table-card'>
                        <h3>$name</h3>
                        <p>$desc</p>
                      </a>";
        }
        $html .= "</div>";
        return $html;
    }

    private function buildTablePageContent(array $table): string {
        $name = ucfirst(htmlspecialchars($table['name']));
        $desc = htmlspecialchars($table['description'] ?? '');
        
        $html = "<h1 class='page-title'>$name</h1>
                 " . ($desc ? "<p class='page-desc'>$desc</p>" : "");

        $html .= "<table class='data-table'>
                    <thead>
                        <tr>
                            <th style='width: 25%'>Column</th>
                            <th style='width: 20%'>Type</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>";

        foreach ($table['columns'] ?? [] as $col) {
            $colName = htmlspecialchars($col['name'] ?? '');
            $type = htmlspecialchars($col['type'] ?? '');
            $note = htmlspecialchars($col['note'] ?? '');
            $constraints = $col['constraints'] ?? [];
            $references  = $col['references']  ?? '';

            $isPk  = in_array('PK', $constraints) || in_array('PRIMARY KEY', $constraints);
            $isFk  = in_array('FK', $constraints) || !empty($references);
            $isUn  = in_array('UNIQUE', $constraints) || in_array('UN', $constraints);

            $tags = '';
            if ($isPk) $tags .= '<span class="badge badge-pk">PK</span>';
            if ($isFk) $tags .= '<span class="badge badge-fk">FK</span>';
            if ($isUn) $tags .= '<span class="badge badge-un">UNIQUE</span>';

            $noteHtml = "<div class='notes'>$note</div>";
            if ($references) {
                $noteHtml .= "<div class='ref'>&rarr; references <a href='{$references}.html'>$references</a></div>";
            }

            $html .= "<tr>
                        <td>
                            <div class='col-name'>$colName</div>
                            <div class='tags-wrapper'>$tags</div>
                        </td>
                        <td class='col-type'>$type</td>
                        <td>$noteHtml</td>
                      </tr>";
        }

        $html .= "</tbody></table>";
        return $html;
    }
}
?>
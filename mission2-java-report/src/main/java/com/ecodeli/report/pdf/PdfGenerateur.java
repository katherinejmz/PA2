package com.ecodeli.report.pdf;

import org.apache.pdfbox.pdmodel.PDDocument;
import org.apache.pdfbox.pdmodel.PDPage;
import org.apache.pdfbox.pdmodel.PDPageContentStream;
import org.apache.pdfbox.pdmodel.graphics.image.PDImageXObject;

import java.io.File;
import java.io.IOException;
import java.util.List;

public class PdfGenerateur {

    public static void genererRapportAvecGraphiques(List<String> cheminsGraphiques, String cheminPdf)
            throws IOException {
        try (PDDocument document = new PDDocument()) {
            // 2 pages, 4 images par page (8 images max)
            for (int pageIndex = 0; pageIndex < 2; pageIndex++) {
                PDPage page = new PDPage();
                document.addPage(page);

                try (PDPageContentStream contentStream = new PDPageContentStream(document, page)) {
                    for (int i = 0; i < 4; i++) {
                        int idx = pageIndex * 4 + i;
                        if (idx >= cheminsGraphiques.size())
                            break;

                        PDImageXObject image = PDImageXObject.createFromFile(cheminsGraphiques.get(idx), document);
                        // Positionnement 2x2 images par page
                        int x = (i % 2) * 280 + 50;
                        int y = (i / 2) * 350 + 150;
                        contentStream.drawImage(image, x, 700 - y, 250, 200);
                    }
                }
            }

            File fichier = new File(cheminPdf);
            if (fichier.getParentFile() != null && !fichier.getParentFile().exists()) {
                fichier.getParentFile().mkdirs();
            }
            document.save(cheminPdf);
            System.out.println("PDF généré : " + cheminPdf);
        }
    }
}

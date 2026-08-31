from reportlab.lib.pagesizes import A4
from reportlab.lib import colors
from reportlab.platypus import SimpleDocTemplate, Paragraph, Spacer, Table, TableStyle
from reportlab.lib.styles import getSampleStyleSheet, ParagraphStyle
from reportlab.lib.units import cm

def create_pdf():
    pdf_path = r"D:\MYWOWIN\FORM_API_KLIEN_PT_WOWIN_PURNOMO_PUTERA.pdf"
    doc = SimpleDocTemplate(
        pdf_path,
        pagesize=A4,
        leftMargin=1.5*cm,
        rightMargin=1.5*cm,
        topMargin=1.2*cm,
        bottomMargin=1.0*cm
    )

    styles = getSampleStyleSheet()
    
    title_style = ParagraphStyle(
        'TitleStyle',
        parent=styles['Heading1'],
        fontSize=13,
        leading=16,
        alignment=1, # Center
        fontName="Helvetica-Bold",
        spaceAfter=10
    )
    
    cell_style = ParagraphStyle(
        'CellStyle',
        parent=styles['Normal'],
        fontSize=9,
        leading=12,
        fontName="Helvetica"
    )
    
    cell_bold = ParagraphStyle(
        'CellBold',
        parent=styles['Normal'],
        fontSize=9,
        leading=12,
        fontName="Helvetica-Bold"
    )
    
    cell_sub = ParagraphStyle(
        'CellSub',
        parent=styles['Normal'],
        fontSize=9,
        leading=12,
        fontName="Helvetica",
        leftIndent=10
    )

    header_bar_style = ParagraphStyle(
        'HeaderBarStyle',
        parent=styles['Normal'],
        fontSize=9,
        leading=12,
        fontName="Helvetica-Bold",
        textColor=colors.black
    )
    
    sig_style = ParagraphStyle(
        'SigStyle',
        parent=styles['Normal'],
        fontSize=9.5,
        leading=13.5,
        alignment=1, # Center
        fontName="Helvetica"
    )
    
    story = []

    # 1. Title
    story.append(Paragraph("FORM API KLIEN", title_style))
    story.append(Spacer(1, 4))

    # 2. Table Data
    raw_data = [
        [Paragraph("BIG AREA", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
        [Paragraph("DIAJUKAN OLEH", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
        [Paragraph("NIK KARYAWAN", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
        [Paragraph("NOMOR WA KARYAWAN", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
        [Paragraph("Tanggal Pengajuan", cell_bold), Paragraph(":", cell_style), Paragraph("26 Agustus 2026", cell_style)],
        [Paragraph("LEVEL VIP", cell_bold), Paragraph(":", cell_style), Paragraph("AGENT / DP * (Diisi J&amp;T)", cell_style)],
        
        # Header Section 1
        [Paragraph("MENU YANG DIAJUKAN", header_bar_style), "", ""],
        [Paragraph("- Order", cell_sub), Paragraph(":", cell_style), Paragraph("<b>YA</b> / <strike>TIDAK</strike> *", cell_style)],
        [Paragraph("- Track", cell_sub), Paragraph(":", cell_style), Paragraph("<b>YA</b> / <strike>TIDAK</strike> *", cell_style)],
        [Paragraph("- Tariff Check", cell_sub), Paragraph(":", cell_style), Paragraph("<b>YA</b> / <strike>TIDAK</strike> *", cell_style)],
        [Paragraph("- Order Cancellation", cell_sub), Paragraph(":", cell_style), Paragraph("<b>YA</b> / <strike>TIDAK</strike> *", cell_style)],
        [Paragraph("LAYANAN", cell_bold), Paragraph(":", cell_style), Paragraph("<b>EZ</b> / <strike>JSD</strike> / <b>JND</b> / <b>ECO</b> *", cell_style)],
        [Paragraph("COD", cell_bold), Paragraph(":", cell_style), Paragraph("<strike>YA</strike> / <b><font color='#b91c1c'>TIDAK</font></b> *", cell_style)],
        
        # Header Section 2
        [Paragraph("MENU YANG DIAJUKAN", header_bar_style), "", ""],
        [Paragraph("- URL Website / APP", cell_sub), Paragraph(":", cell_style), Paragraph("<b>https://mywowin.com</b> (Aplikasi Android: <i>My Wowin</i>)", cell_style)],
        [Paragraph("- IP Address Website / APP", cell_sub), Paragraph(":", cell_style), Paragraph("<b>153.92.12.241</b>", cell_style)],
        
        # Header Section 3
        [Paragraph("DETAIL DATA KLIEN", header_bar_style), "", ""],
        [Paragraph("Nama klien / PT", cell_bold), Paragraph(":", cell_style), Paragraph("<b>PT WOWIN PURNOMO PUTERA</b>", cell_style)],
        [Paragraph("Nama Akun (sistem)", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
        [Paragraph("Alamat", cell_bold), Paragraph(":", cell_style), Paragraph("Jl. Raya No. KM 07, Duwet, Ngetal, Kecamatan Pogalan, Kabupaten Trenggalek, Jawa Timur 66371", cell_style)],
        [Paragraph("Nomor Telepon", cell_bold), Paragraph(":", cell_style), Paragraph("0812-1630-1220", cell_style)],
        [Paragraph("NPWP", cell_bold), Paragraph(":", cell_style), Paragraph("<b>66.375.783.9-629.000</b> (NPWP16: 0663 7578 3962 9000)", cell_style)],
        [Paragraph("Metode Pembayaran", cell_bold), Paragraph(":", cell_style), Paragraph("Bulanan", cell_style)],
        [Paragraph("Nama Owner", cell_bold), Paragraph(":", cell_style), Paragraph("<b>William Purnomo</b>", cell_style)],
        [Paragraph("Nama DP", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
        [Paragraph("Kode DP", cell_bold), Paragraph(":", cell_style), Paragraph("<i>Diisi J&amp;T</i>", cell_style)],
    ]

    col_widths = [160, 15, 335]
    table = Table(raw_data, colWidths=col_widths, repeatRows=0)
    
    t_style = [
        ('GRID', (0,0), (-1,-1), 1, colors.black),
        ('VALIGN', (0,0), (-1,-1), 'MIDDLE'),
        ('TOPPADDING', (0,0), (-1,-1), 3),
        ('BOTTOMPADDING', (0,0), (-1,-1), 3),
        ('LEFTPADDING', (0,0), (-1,-1), 5),
        ('RIGHTPADDING', (0,0), (-1,-1), 5),
        # Span headers
        ('SPAN', (0,6), (2,6)),
        ('BACKGROUND', (0,6), (2,6), colors.HexColor('#e5e7eb')),
        ('SPAN', (0,13), (2,13)),
        ('BACKGROUND', (0,13), (2,13), colors.HexColor('#e5e7eb')),
        ('SPAN', (0,16), (2,16)),
        ('BACKGROUND', (0,16), (2,16), colors.HexColor('#e5e7eb')),
    ]
    table.setStyle(TableStyle(t_style))
    story.append(table)
    story.append(Spacer(1, 16))

    # 3. Signature Block
    sig_data = [
        [
            "", 
            Paragraph("Trenggalek, 26 Agustus 2026<br/><b>PT WOWIN PURNOMO PUTERA</b><br/><br/><br/><br/><br/><b>( William Purnomo )</b><br/><span>Owner / Penanggung Jawab</span>", sig_style)
        ]
    ]
    sig_table = Table(sig_data, colWidths=[270, 240])
    sig_table.setStyle(TableStyle([
        ('VALIGN', (0,0), (-1,-1), 'TOP'),
        ('ALIGN', (1,0), (1,0), 'CENTER'),
    ]))
    story.append(sig_table)
    
    story.append(Spacer(1, 10))
    story.append(Paragraph("<font size=8 color='#4b5563'>* Coret yang tidak perlu</font>", cell_style))

    doc.build(story)
    print("PDF generated successfully (Single Page) at:", pdf_path)

if __name__ == '__main__':
    create_pdf()

<?php include "header.php"?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOI Form</title>
    <style>

    /* Print-specific styles */
    @media print {
        .no-print {
            display: none;
        }
    }

    #outputSection {
        border: 1px solid #000;
        padding: 20px;
        margin: 20px auto;
        max-width: 800px;
    }

    h1,
    h3 {
        text-align: center;
        text-decoration: underline;
    }

    .page {
        page-break-after: always;
    }

    .page:last-child {
        page-break-after: auto;
    }
    </style>
</head>

<body>
    <div id="content" class="pt-5">
        <div class="title">
            <h2>Letter of Intent Form</h2>
        </div>

        <!-- Input Form -->
        <div id="inputSection" class="no-print ">
            <div class="user__details">
                <div class="input__box mx-2">
                    <label for="dealerName">Dealer Name:</label><br>
                    <input type="text" id="name" placeholder="Enter Dealer Name"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="dealerAddress">Dealer Address:</label><br>
                    <input type="text" id="address" placeholder="Enter Dealer Address"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="dealerArea">Area:</label><br>
                    <input type="text" id="area" placeholder="Enter Dealer Area"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="appointedDate">Date:</label><br>
                    <input type="date" id="date"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="brandName">Brand/Product Name:</label><br>
                    <input type="text" id="brandName" placeholder="Enter Brand/Product Name"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="brandName">Principal Name:</label><br>
                    <input type="text" id="pri-name" placeholder="Enter Principal Name"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="amount">Amount:</label><br>
                    <input type="text" id="amount" placeholder="Enter Amount"><br><br>
                </div>
                <div class="input__box mx-2">
                    <label for="amount">Generate Form</label><br>
                    <button onclick="generateLOI()" class="print-btn">Generate LOI</button>
                </div>
            </div>
        </div>


        <script>
        function generateLOI() {
            const dealerName = document.getElementById("name").value;
            const dealerAddress = document.getElementById("address").value;
            const dealerArea = document.getElementById("area").value;
            const appointedDate = document.getElementById("date").value;
            const brandName = document.getElementById("brandName").value;
            const amount = document.getElementById("amount").value;
            const priName = document.getElementById("pri-name").value;
            const currentDate = new Date();

            // Validate inputs
            if (!dealerName || !dealerAddress || !dealerArea || !appointedDate || !brandName ||
                !amount || !priName) {
                alert("Please fill out all fields before generating the LOI.");
                return;
            }

            // Format dates
            const currentFormattedDate = currentDate.toLocaleDateString('en-GB');
            currentDate.setFullYear(currentDate.getFullYear() + 5); // Add 5 years
            const expiryDate = currentDate.toLocaleDateString('en-GB');

            // Create a new tab and write the LOI content
            const newTab = window.open("", "_blank");
            newTab.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <style>
              body {
               
                    margin: 20px;
                    line-height: 1.6;
                }
            
                .reference {
                display: flex;
                justify-content: space-between;
            }
                .page h1{
                font-size: 45px;
                color: red;
                font-weight: 600;
                font-family: auto;
                text-align:center;
                margin: 0px;
                text-transform: uppercase;
                    }
              
                h2 {
                    text-align: center;
                }
                p {
                    margin: 10px 0;
                }
                     @media print {
                button {
                    display: none;
                }
                }

    .sub-text{
        font-size: 12px;
        color: #2f5e87;
        font-weight: 500;
        font-family: sans-serif;
        text-align:center;
        border-bottom: 5px solid;
    }
        .content p, li{
            font-family: sans-serif;
        }
            .content img{
            width:209px;
            }
            .content  h3 {
                font-size: 19px;
                margin: 0px;
                text-decoration: underline;
                font-family: system-ui;
            }
        .page {
                    page-break-after: always; 
                }
            </style>
        </head>
        <body>
          
    <div id="outputSection">

<div class="page">
            <!-- Input Form -->
            <!-- Header Section -->
            <div class="header">
           
                <h1 class="heading">RAYON ENGINEERS</h1>
                <div class="sub-text">
                <p>VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, New Delhi, South West Delhi, Delhi, 110043<br>
                Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST No: 07ABCFR8497H1ZM</p>
                    </div>
            </div>
            <!-- Reference Section -->
            <div class="reference">
                <p>Ref No. <span class="italic underline" id="refNumber">RE/2024-25/1</span></p>
                <p>Dated: <span class="italic underline">${appointedDate}</span></p>
            </div>

            <!-- Content Section -->
            <div class="content">
                <p>Please ensure that the infrastructure is complete and ready for activation latest by <span
                        class="bold">${appointedDate}</span>. In case of any further delay, the letter shall be treated
                    as
                    cancelled unless otherwise mutually agreed to.</p>
                <p>On your fulfilling the requirements and all aforesaid terms and conditions as per this LOI to
                    COMPANY’S
                    satisfaction, the regular Dealer Agreement shall be executed by us after completion of the
                    period of
                    this provisional Dealership.</p>
                <p>Please return the duplicate copy of this letter duly signed by you as a token of your acceptance.
                </p>
                <p>Thanking you,</p>
                <p>Your faithfully,</p>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <p class="bold">Authorized Signatory</p>
                <p>Encls: Annexure ‘A’</p>
                <p>Schedule 1, 2 & 3</p>
            </div>

            <!-- Dealer Details -->
            <div class="dealer-details">
                <p>We accept the above terms & conditions:</p>
                <p>Dealer Name: <span class="bold">${dealerName}</span></p>
                <p>Dealer Principal Name: <span class="bold">${priName}</p>
                <p>Signature:</p>
                <p>Dated: <span class="italic underline">${appointedDate}</span></p>
            </div>

            <!-- Footer Signature -->
            <div class="footer-signature">
                <p>For <span class="bold">RAYON ENGINEERS</span></p>
                <p><span class="underline">Anamika</span></p>
                <p>Partner</p>
        <img src="../images/signature.png" alt="">
                
            </div>

            </div>
            <!-- SECOND FROM -->
            <hr>
<div class="page">
            <!-- Header -->
            <div class="header">
                <h1>RAYON ENGINEERS</h1>
                <p class="sub-text">
                    VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, NEW DELHI, SOUTH WEST DELHI, DELHI, 110043<br>
                    Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST
                    No: 07ABCF8497H1ZM
                </p>
            </div>

            <!-- Reference and Date -->
            <div class="reference">
                <p>Ref No. <span class="italic underline" id="refNumber">RE/2024-25/1</span></p>
                <p>Dated: <span class="italic underline">${appointedDate}</span></p>
            </div>

            <!-- Recipient -->
            <div class="to-section">
                <p>To<br>
                    The Regional Transport Office,<br>
                    ${dealerArea}
                </p>
            </div>

            <!-- Content -->
            <div class="content">
                <p>
                    This is for your kind information that <span class="highlight">${priName} ,
                        <strong>${dealerAddress}</strong> is appointed as the Authorized Dealer for Sales, Service,
                        and spares
                        for our <span class="highlight">ICAT</span> approved E-Rickshaw, <span class="highlight">${brandName}</span>
                        at NAWADA BIHAR.
                </p>
                <p>
                    You are requested to please register the E-Rickshaw AND E CART <span class="highlight">${brandName}</span> presented for Registration.
                </p>
            </div>

            <!-- Signature -->
            <div class="signature">
                <p>M/S RAYON ENGINEERS.</p>
                <div class="sign">
        <img src="../images/signature.png" alt="">

                </div>
                <p class="authorized">(AUTHORISED SIGNATORY)</p>
            </div>
            </div>
            <!-- THIRD FORM IS HERE -->
            <div class="page">
 
            <!-- Header -->
            <div class="header">
                <h1>RAYON ENGINEERS</h1>
                  <p class="sub-text">
                    VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, NEW DELHI, SOUTH WEST DELHI, DELHI, 110043<br>
                    Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST
                    No: 07ABCF8497H1ZM
                </p>
            </div>

            <!-- Reference and Date -->
                  <div class="reference">
                <p>Ref No. <span class="italic underline" id="refNumber">RE/2024-25/1</span></p>
                <p>Dated: <span class="italic underline">${appointedDate}</span></p>
            </div>

            <!-- Documents Required -->
            <div class="documents-section">
                <h3>Documents Required</h3>
                <p>Dealer is required to furnish the following documents for the records of the company and keep the
                    company
                    updated on any changes there of during the term of this LOI.</p>

                <table>
                    <tr>
                        <th>Sr.no.</th>
                        <th>REQUIREMENTS</th>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>Registration/approval of company</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Copy of Memorandum & Articles of Association of your Company/Partnership
                            deed/Proprietorship
                            firm</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td>Copy of Pan card</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td>Security deposit</td>
                    </tr>
                    <tr>
                        <td>5</td>
                        <td>CST, LST, VAT, GST, Service Tax, Documents</td>
                    </tr>
                    <tr>
                        <td>6</td>
                        <td>Photographs of approval site(Showroom, Workshop Interior and Exterior)</td>
                    </tr>
                    <tr>
                        <td>7</td>
                        <td>Layout of approved site plan with all critical dimensions</td>
                    </tr>
                    <tr>
                        <td>8</td>
                        <td>City Map with site location</td>
                    </tr>
                    <tr>
                        <td>9</td>
                        <td>Ownership/Rental agreement of showroom and workshop</td>
                    </tr>
                    <tr>
                        <td>10</td>
                        <td>Post card size photographs of proposed Showroom, workshop interior and exterior</td>
                    </tr>
                    <tr>
                        <td>11</td>
                        <td>PP size photographs of Proprietor/Partners/Directors</td>
                    </tr>
                </table>

                <p class="footer"> Please ensure that the above documents / details reach us by <span
                        class="italic underline" id="outputDate1">${appointedDate}</span></p>
        <img src="../images/signature.png" alt="">


            </div>

            <!-- Signature -->
            <div class="signature">
                <p>For RAYON ENGINEERS</p>
                <div class="sign">
                    <p><strong>Amanika</strong></p>
                    <p>Partner</p>
                </div>
            </div>
            </div>
            <!-- FOUR FORM  -->
            <div class="page">
            <div class="header">
                <h1>RAYON ENGINEERS</h1>
            <p class="sub-text">
                    VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, NEW DELHI, SOUTH WEST DELHI, DELHI, 110043<br>
                    Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST
                    No: 07ABCF8497H1ZM
                </p>
            </div>

            <div class="content">
                <h2>Dealer Agreement</h2>
                <p><strong>Ref No:</strong> RE/2024-25/97</p>
                <p><strong>Date:</strong><span id="outputDate1">${appointedDate}</span></p>
                <p>Dealer is required to arrange trained mechanics at his dealership who will be able to attend all
                    major and minor repairs. It is the prime responsibility of the dealer to arrange adequate service at
                    his dealership. It is also the prime responsibility of the dealer to educate customers regarding
                    service and maintenance of vehicles.</p>

                <h3>SPARE PARTS</h3>
                <p>You are required to arrange all essential spare parts at your dealership as though 95% of spare parts
                    used in our vehicles are easily available in the local market, but we suggest you keep original
                    spares stock with you for our vehicles. You are advised to place an order for the same with a Demand
                    Draft of <strong><span id="outputAmt">${amount}</span></strong> so that the same can be arranged for you
                    along with
                    your
                    vehicles. Any
                    consumer case will be the sole responsibility of the dealer.</p>

                <h3>BODY</h3>
                <p>No warranty is applicable on the body of the vehicle, which includes paints, seats, metal sheet,
                    welding whatsoever.</p>

                <h3>SALE PRICE OF VEHICLE</h3>
                <p>You are advised to sell the vehicle as per rates mutually decided among you and the company. Any
                    violation will automatically cancel the dealership.</p>

                <h3>DISPUTES</h3>
                <p>Only the Delhi Court will have jurisdiction to decide any dispute arising out of this appointment.
                    You will represent any dispute under the Consumer Forum where the company is made the party, and you
                    will undertake to file the necessary counterclaims such cases if required. We welcome you to the
                    family of <strong><span id="outputBrandName">${brandName}</span></strong>, which is well known for the quality
                    and standard
                    of their E-rickshaws and Load Carriers, and sincerely hope that this association will be mutually
                    beneficial to both organizations.</p>

                <div class="signature">
                    <p>With regards,</p>
                    <p>Your faithfully,</p>
                    <p><em>No Dealership letter is valid until the Dealer Declaration is submitted to the company.</em>
                    </p>
                </div>

                <p><strong>ALL PAYMENTS ARE REQUIRED TO BE PAID THROUGH DEMAND DRAFTS OR RTGS.</strong></p>
        <img src="../images/signature.png" alt="">

            </div>
</div>
            <!-- FIFTH PAGE -->
            <div class="page">
            <div class="header">
                <h1>RAYON ENGINEERS</h1>
                    <p class="sub-text">
                    VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, NEW DELHI, SOUTH WEST DELHI, DELHI, 110043<br>
                    Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST
                    No: 07ABCF8497H1ZM
                </p>
            </div>

            <div class="content">
                <h2>LETTER OF INTENT</h2>
                <p><strong>Ref No:</strong> RE/2024-25/97</p>
                <p><strong>Date: <span id="outputDate1">${appointedDate}</span></strong> </p>

                <p>TO WHOMSOEVER IT MAY CONCERN</p>

                <p>M/S Rayon Engineers authorizes <strong><span id="outputDealerName">${dealerName}</span></strong>, proprietor of
                    <span id="outputpri"></span>, <span id="outputDealerAddress">${dealerAddress}</span> to appoint the
                    DEALERSHIP (<span id="outputArea">${dealerArea}</span>) in
                    our
                    product/vehicle
                    <strong>"E-Rickshaw
                        "E-CART"</strong> under the brand name <strong><span id="outputBrandName">${brandName}</span></strong>.
                </p>

                <div class="signature">
                    <p>M/S Rayon Engineers.</p>
                    <p>For Rayon Engineers,</p>
                    <p>Amarika</p>
        <img src="../images/signature.png" alt="">

                    <p>(Authorized Signatory)</p>
                </div>
            </div>

            <div class="footer">
                <p>&copy; 2024 Rayon Engineers. All rights reserved.</p>
            </div>
            </div>
            <!-- SIXTH PAGE -->
            <div class="page">
            <div class="header">
                <h1>RAYON ENGINEERS</h1>
                    <p class="sub-text">
                    VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, NEW DELHI, SOUTH WEST DELHI, DELHI, 110043<br>
                    Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST
                    No: 07ABCF8497H1ZM
                </p>
                <p>Ref No. <span class="italic underline" id="refNumber">RE/2024-25/1</span></p>
                <p> Dated <strong><span id="outputDate1">${appointedDate}</span></strong></p>
            </div>
            <div class="content">
                <p>TO,</p>
                <p><strong>THE COMMISSIONER</strong><br>TRANSPORT DEPARTMENT<br>NAWADA BIHAR</p>
                <p>Dear Sir,</p>
                <p>
                    This is to certify that has its registered Address
                    <strong> <span id="outputpri">${priName}</span>, <span id="outputDealerAddress">${dealerAddress}</span></strong>
                    is our authorized DEALERSHIP for Sales Services & Spares of E Rickshaw and E Cart brand name
                    <strong>“ROCKY AND ROCKY CARGO”</strong>.
                </p>
                <p>Letter Is Being Issued For RTO Purpose</p>
                <p>Regards,</p>
                <p><strong>M/S RAYON ENGINEERS.</strong></p>
                <div class="signature">
                    <p>For RAYON ENGINEERS</p>
                    <p><strong>Authorized Signatory</strong></p>
        <img src="../images/signature.png" alt="">
                    
                </div>
            </div>
            </div>
            <!-- SEVENTH PAGE -->
            <div class="page">
            <div class="header">
                <h1>Rayon Engineers</h1>
                <p class="sub-text">
                    VILLAGE MAKSUDABAD, NEAR PLOE NO-5, NAJAFGARH, NEW DELHI, SOUTH WEST DELHI, DELHI, 110043<br>
                    Email: <a href="mailto:info@rayonengineers.com">info@rayonengineers.com</a> | Mobile:
                    +91-8595686869
                    |
                    GST
                    No: 07ABCF8497H1ZM
                </p>
            </div>
            <div class="content">
                <h2>Dealership Approval</h2>
                <p><strong>Ref No:</strong> RE/2024-25/97</p>
                <p><strong>Date:</strong> <span id="outputDate1">${appointedDate}</span></p>
                <p><strong>Valid Upto:</strong> <span id="outputDate2">${currentDate}</span></p>
                <p><strong>To:</strong> <span id="outputDealerAddress">${dealerAddress}</span></p>
                <p></strong> <span id="outputArea">${dealerArea}</span></p>
                <p><strong>GST No:</strong> 10HBVPS4820P1ZV</p>
                <p><strong>Attention:</strong><span id="outputDealerName">${dealerName}</span></p>

                <p>Dear Sir,</p>
                <p>DEALERSHIP APPROVAL for <strong><span id="outputBrandName">${brandName}</span></strong> Battery Tricycles,<span
                        id="outputDealerAddress">${dealerAddress}</span> We are pleased to appoint you as our Authorized Dealer for the
                    sale and
                    service of
                    <strong><strong><span id="outputBrandName">${brandName}</span></strong></strong> Battery Tricycles for Nawada,
                    which comes under Bihar, on the
                    following terms and conditions:
                </p>

                <h3>Terms and Conditions</h3>
                <ul>
                    <li><strong>Area:</strong> Your area of operation shall be <strong><span
                                id="outputArea">${dealerArea}</span></strong>.</li>
                    <li><strong>Prices:</strong> Present prices of various models shall be as per the Price List
                        enclosed. These prices are subject to revision.</li>
                    <li><strong>Dealer's Commission:</strong> Prices include dealer's commission; no separate commission
                        is payable.</li>
                    <li><strong>Dealer Deposit:</strong> <strong><span id="outputAmt">${amount}</span> as refundable security
                            deposit.</li>
                    <li><strong>Sales and Delivery:</strong> Initial purchase of 10 vehicles is mandatory, with a
                        monthly sales target of 10 vehicles in your district.</li>
                </ul>

                <div class="signature">
                    <p>For Rayon Engineers,</p>
                    <p>Amarika</p>
                            <img src="../images/signature.png" alt="">
                    <p>(Partner)</p>
                </div>
            </div>
            <div class="footer">
                <p>&copy; 2024 Rayon Engineers. All rights reserved.</p>
            </div>
            </div>
        </div>
 
       <button onclick="window.print()">Print</button>
        </body>
        </html>
    `);

            newTab.document.close(); // Close the document to finish rendering
        }
        </script>
        <script src="../js/navcss.js"></script>

        <script>
        // Start reference number from 1
        let refNumber = 1;

        // When the page loads or the form is submitted, update the reference number
        document.addEventListener('DOMContentLoaded', function() {
            // Update the reference number and date on page load
            document.getElementById('refNumber').textContent = `RE/2024-25/${refNumber}`;
            document.getElementById('appointedDate').textContent = new Date().toLocaleDateString('en-GB');
        });

        // If you want to update the reference number each time the form is submitted
        document.getElementById('myForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent actual form submission

            // Increment the reference number and update it in the DOM
            refNumber++;
            document.getElementById('refNumber').textContent = `RE/2024-25/${refNumber}`;

            // Update the date in the DOM
            document.getElementById('appointedDate').textContent = new Date().toLocaleDateString('en-GB');
        });
        </script>
</body>

</html>
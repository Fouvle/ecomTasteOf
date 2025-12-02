<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Become a Vendor | TasteConnect</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --primary-orange: #ea580c;
            --primary-hover: #c2410c;
            --dark-text: #111827;
            --gray-text: #6b7280;
            --light-bg: #f9fafb;
            --border-color: #e5e7eb;
            --font-main: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            font-family: var(--font-main);
            background-color: var(--light-bg);
            color: var(--dark-text);
            margin: 0;
            padding: 0;
        }

        /* Navbar */
        .navbar {
            background: white;
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo { font-weight: 800; font-size: 1.5rem; color: var(--primary-orange); text-decoration: none; }
        .nav-btn { padding: 0.5rem 1rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 0.9rem; }
        .btn-login { border: 1px solid var(--border-color); color: var(--dark-text); margin-right: 1rem; }

        /* Container */
        .container { max-width: 800px; margin: 3rem auto; padding: 0 1.5rem; }
        
        .header-section { margin-bottom: 2rem; }
        .header-section h1 { font-size: 2.2rem; margin-bottom: 0.5rem; color: var(--dark-text); }
        .header-section p { color: var(--gray-text); font-size: 1.1rem; }

        /* Progress Bar (Matches Screenshot Style) */
        .progress-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3rem;
            position: relative;
            padding: 0 1rem;
        }
        .progress-bar-bg {
            position: absolute;
            top: 15px;
            left: 0;
            width: 100%;
            height: 3px;
            background: #e5e7eb;
            z-index: 0;
        }
        .progress-bar-fill {
            position: absolute;
            top: 15px;
            left: 0;
            height: 3px;
            background: var(--primary-orange);
            z-index: 0;
            transition: width 0.3s ease;
            width: 0%; /* JS will update this */
        }
        
        .step-wrapper {
            position: relative;
            z-index: 1;
            text-align: center;
            width: 80px;
        }
        .step {
            background: white;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: var(--gray-text);
            margin: 0 auto 0.5rem auto;
            transition: 0.3s;
        }
        .step.active {
            border-color: var(--primary-orange);
            background: var(--primary-orange);
            color: white;
        }
        .step.completed {
            background: var(--primary-orange);
            border-color: var(--primary-orange);
            color: white;
        }
        .step-label { font-size: 0.8rem; color: var(--gray-text); }
        .step.active + .step-label { color: var(--primary-orange); font-weight: 600; }

        /* Form Styling */
        .form-section {
            background: white;
            padding: 2.5rem;
            border-radius: 12px;
            border: 1px solid var(--border-color);
            display: none; /* Hidden by default */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }
        .form-section.current { display: block; animation: fadeIn 0.4s ease; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        .section-header { font-size: 1.3rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--dark-text); }

        .form-group { margin-bottom: 1.5rem; }
        .form-label { display: block; margin-bottom: 0.5rem; font-weight: 600; font-size: 0.95rem; }
        
        .form-control {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 1rem;
            box-sizing: border-box;
            font-family: inherit;
            background: #f9fafb;
        }
        .form-control:focus { outline: none; border-color: var(--primary-orange); background: white; box-shadow: 0 0 0 3px rgba(234, 88, 12, 0.1); }

        /* Checkbox Grid */
        .checkbox-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
        .checkbox-item { display: flex; align-items: center; gap: 0.6rem; font-size: 0.95rem; cursor: pointer; }
        .checkbox-item input { accent-color: var(--primary-orange); width: 18px; height: 18px; }

        /* Buttons */
        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 2rem;
            border-top: 1px solid #f3f4f6;
            padding-top: 1.5rem;
        }
        .btn {
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 1rem;
            transition: 0.2s;
        }
        .btn-next { background: var(--primary-orange); color: white; }
        .btn-next:hover { background: var(--primary-hover); }
        .btn-prev { background: white; border: 1px solid #d1d5db; color: var(--dark-text); }
        .btn-prev:hover { background: #f3f4f6; }
        
        .file-box {
            border: 2px dashed #d1d5db;
            padding: 2rem;
            text-align: center;
            border-radius: 8px;
            cursor: pointer;
            background: #f9fafb;
            transition: 0.2s;
        }
        .file-box:hover { border-color: var(--primary-orange); background: #fff7ed; }

    </style>
</head>
<body>

    <nav class="navbar">
        <a href="../index.php" class="logo">TC TasteConnect</a>
        <div>
            <span style="margin-right:1rem; color:var(--gray-text); display:none; @media(min-width:600px){display:inline;}">Already selling?</span>
            <a href="login/vendor_login.php" class="nav-btn btn-login">Login</a>
        </div>
    </nav>

    <div class="container">
        <div class="header-section">
            <a href="../index.php" style="text-decoration:none; color:var(--dark-text); font-size:0.9rem; margin-bottom:1rem; display:inline-block;"><i class="fas fa-arrow-left"></i> Back to Home</a>
            <h1>Become a Vendor</h1>
            <p>Join Ghana's premier food experience platform in 4 easy steps.</p>
        </div>

        <!-- Progress Steps -->
        <div class="progress-container">
            <div class="progress-bar-bg"></div>
            <div class="progress-bar-fill" id="progressFill"></div>
            
            <div class="step-wrapper">
                <div class="step active" data-step="1">1</div>
                <div class="step-label">Business Info</div>
            </div>
            <div class="step-wrapper">
                <div class="step" data-step="2">2</div>
                <div class="step-label">Location</div>
            </div>
            <div class="step-wrapper">
                <div class="step" data-step="3">3</div>
                <div class="step-label">Operations</div>
            </div>
            <div class="step-wrapper">
                <div class="step" data-step="4">4</div>
                <div class="step-label">Legal & Payment</div>
            </div>
        </div>

        <form id="vendorForm" enctype="multipart/form-data">
            
            <!-- STEP 1: Business Info -->
            <div class="form-section current" id="step1">
                <div class="section-header">Business Information</div>
                
                <div class="form-group">
                    <label class="form-label">Business Name *</label>
                    <input type="text" name="business_name" class="form-control" placeholder="e.g., Mama Esi's Kitchen" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Type *</label>
                    <select name="business_type" class="form-control" required>
                        <option value="">Select business type</option>
                        <option value="Restaurant">Restaurant</option>
                        <option value="Street Food Vendor">Street Food Vendor</option>
                        <option value="Catering Service">Catering Service</option>
                        <option value="Food Truck">Food Truck</option>
                        <option value="Home Kitchen">Home Kitchen</option>
                        <option value="Chop Bar">Chop Bar</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Cuisine Types * (Select all that apply)</label>
                    <div class="checkbox-grid">
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Traditional Ghanaian"> Traditional Ghanaian</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Continental"> Continental</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Street Food"> Street Food</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Vegan/Vegetarian"> Vegan/Vegetarian</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Seafood"> Seafood</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Soups & Stews"> Soups & Stews</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Grilled & BBQ"> Grilled & BBQ</label>
                        <label class="checkbox-item"><input type="checkbox" name="cuisine_type[]" value="Fusion"> Fusion</label>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Description *</label>
                    <textarea name="business_description" class="form-control" rows="4" placeholder="Tell us about your food, specialty dishes..." required minlength="50"></textarea>
                    <small style="color:gray;">Min 50 characters</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Price Range *</label>
                    <select name="price_range" class="form-control" required>
                        <option value="">Select price range</option>
                        <option value="Low">Low (₵)</option>
                        <option value="Medium">Medium (₵₵)</option>
                        <option value="High">High (₵₵₵)</option>
                    </select>
                </div>

                <div class="btn-group">
                    <div></div> 
                    <button type="button" class="btn btn-next" onclick="nextStep(1)">Next Step</button>
                </div>
            </div>

            <!-- STEP 2: Location -->
            <div class="form-section" id="step2">
                <div class="section-header">Location & Contact</div>

                <div class="form-group">
                    <label class="form-label">Street Address *</label>
                    <input type="text" name="business_address" class="form-control" placeholder="e.g., 123 Liberation Road, Osu" required>
                </div>

                <div class="form-group">
                    <label class="form-label">City *</label>
                    <select name="business_city" class="form-control" required>
                        <option value="">Select city</option>
                        <option value="Accra">Accra</option>
                        <option value="Kumasi">Kumasi</option>
                        <option value="Takoradi">Takoradi</option>
                        <option value="Tamale">Tamale</option>
                        <option value="Cape Coast">Cape Coast</option>
                        <option value="Tema">Tema</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Phone Number *</label>
                    <input type="tel" name="business_phone" class="form-control" placeholder="+233 24 123 4567" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Email *</label>
                    <input type="email" name="business_email" class="form-control" placeholder="contact@yourbusiness.com" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Website (Optional)</label>
                    <input type="url" name="website" class="form-control" placeholder="https://www.yourbusiness.com">
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-prev" onclick="prevStep(2)">Previous</button>
                    <button type="button" class="btn btn-next" onclick="nextStep(2)">Next Step</button>
                </div>
            </div>

            <!-- STEP 3: Operations -->
            <div class="form-section" id="step3">
                <div class="section-header">Operating Details</div>

                <div class="form-group">
                    <label class="form-label">Seating Capacity *</label>
                    <input type="number" name="seating_capacity" class="form-control" placeholder="e.g., 30" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Operating Days *</label>
                    <div class="checkbox-grid">
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Monday"> Monday</label>
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Tuesday"> Tuesday</label>
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Wednesday"> Wednesday</label>
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Thursday"> Thursday</label>
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Friday"> Friday</label>
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Saturday"> Saturday</label>
                        <label class="checkbox-item"><input type="checkbox" name="operating_days[]" value="Sunday"> Sunday</label>
                    </div>
                </div>

                <div style="display:flex; gap:1rem;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Opening Time *</label>
                        <input type="time" name="opening_time" class="form-control" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Closing Time *</label>
                        <input type="time" name="closing_time" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Menu Upload (Optional)</label>
                    <div class="file-box" onclick="document.getElementById('menuFile').click()">
                        <i class="fas fa-cloud-upload-alt" style="font-size:2rem; color:var(--gray-text);"></i>
                        <p style="margin:0.5rem 0;">Click to upload Menu PDF or Image</p>
                        <input type="file" name="menu_file" id="menuFile" style="display:none;" accept=".pdf,.jpg,.png">
                        <span id="fileName" style="font-size:0.9rem; color:var(--primary-orange); font-weight:bold;"></span>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-prev" onclick="prevStep(3)">Previous</button>
                    <button type="button" class="btn btn-next" onclick="nextStep(3)">Next Step</button>
                </div>
            </div>

            <!-- STEP 4: Legal & Payment -->
            <div class="form-section" id="step4">
                <div class="section-header">Legal & Payment Information</div>

                <div style="background:#fff7ed; padding:1rem; border-radius:8px; margin-bottom:1.5rem; font-size:0.95rem; color:#c2410c; border:1px solid #fed7aa;">
                    <i class="fas fa-user-lock"></i> <strong>Account Setup:</strong> Please provide your details to create your vendor account login.
                </div>

                <!-- Owner Info / Login Info -->
                <div class="form-group">
                    <label class="form-label">Owner Full Name *</label>
                    <input type="text" name="owner_name" class="form-control" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Owner Phone *</label>
                    <input type="tel" name="owner_phone" class="form-control" required>
                </div>

                <div style="display:flex; gap:1rem;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Login Email *</label>
                        <input type="email" name="owner_email" class="form-control" required>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Login Password *</label>
                        <input type="password" name="password" class="form-control" required minlength="6">
                    </div>
                </div>

                <hr style="border:0; border-top:1px solid #eee; margin:2rem 0;">

                <!-- Payment Info -->
                <div style="display:flex; gap:1rem;">
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">Mobile Money Provider *</label>
                        <select name="momo_provider" class="form-control" required>
                            <option value="MTN">MTN Mobile Money</option>
                            <option value="Telecel">Telecel Cash</option>
                            <option value="AT">AT Money</option>
                        </select>
                    </div>
                    <div class="form-group" style="flex:1;">
                        <label class="form-label">MoMo Number *</label>
                        <input type="tel" name="momo_number" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Business Registration Number</label>
                    <input type="text" name="business_reg_no" class="form-control" placeholder="e.g., BN-12345678">
                </div>

                <div class="form-group">
                    <label class="form-label">TIN (Tax Identification Number)</label>
                    <input type="text" name="tin" class="form-control" placeholder="e.g., P0012345678">
                </div>

                <div class="form-group">
                    <label class="checkbox-item" style="align-items:flex-start;">
                        <input type="checkbox" required style="margin-top:4px;"> 
                        <span style="font-size:0.9rem; line-height:1.4;">I agree to the Terms of Service and Privacy Policy. I understand the 15% commission structure on bookings.</span>
                    </label>
                </div>

                <div class="btn-group">
                    <button type="button" class="btn btn-prev" onclick="prevStep(4)">Previous</button>
                    <button type="submit" class="btn btn-next" style="background:#166534; hover:background: #14532d;">Submit Application</button>
                </div>
            </div>

        </form>
    </div>

    <script src="../js/vendor_register.js"></script>
    <script>
        document.getElementById('menuFile').onchange = function() {
            if(this.files.length > 0) {
                document.getElementById('fileName').textContent = "Selected: " + this.files[0].name;
            }
        };
    </script>
</body>
</html>
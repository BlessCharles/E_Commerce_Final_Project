<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlanSmart Ghana - Edit Business Profile</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
        }
        
        /* Navigation Bar */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
        }
        
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #1e3a8a;
        }
        
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        
        .vendor-badge {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ec4899, #db2777);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }
        
        /* Main Content */
        .main-content {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .page-header {
            margin-bottom: 30px;
        }
        
        .page-header h1 {
            font-size: 32px;
            color: #1e3a8a;
            margin-bottom: 8px;
        }
        
        .page-header p {
            font-size: 16px;
            color: #64748b;
        }
        
        /* Form Card */
        .form-card {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }
        
        .form-section {
            margin-bottom: 35px;
        }
        
        .section-title {
            font-size: 20px;
            font-weight: 700;
            color: #1e3a8a;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }
        
        .form-label .required {
            color: #ef4444;
        }
        
        .form-input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-textarea {
            min-height: 120px;
            resize: vertical;
            font-family: inherit;
        }
        
        .form-select {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            cursor: pointer;
        }
        
        /* Image Upload */
        .image-upload-area {
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .image-upload-area:hover {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .upload-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        
        .upload-text {
            font-size: 16px;
            color: #64748b;
            margin-bottom: 5px;
        }
        
        .upload-hint {
            font-size: 13px;
            color: #94a3b8;
        }
        
        /* Checkbox Group */
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        .checkbox-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .checkbox-item:hover {
            border-color: #fbbf24;
            background: #fffbeb;
        }
        
        .checkbox-item input {
            width: 20px;
            height: 20px;
            cursor: pointer;
            accent-color: #fbbf24;
        }
        
        .checkbox-item label {
            cursor: pointer;
            font-size: 15px;
            color: #334155;
        }
        
        /* Form Grid */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }
        
        /* Buttons */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 30px;
            border-top: 2px solid #e2e8f0;
        }
        
        .btn-cancel {
            padding: 14px 32px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-cancel:hover {
            border-color: #cbd5e1;
            background: #f8fafc;
        }
        
        .btn-save {
            padding: 14px 32px;
            background: #fbbf24;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #1e3a8a;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(251, 191, 36, 0.3);
        }
        
        .btn-save:hover {
            background: #f59e0b;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(251, 191, 36, 0.4);
        }
        
        /* Helper Text */
        .helper-text {
            font-size: 13px;
            color: #64748b;
            margin-top: 5px;
        }
        
        /* Verification Status */
        .verification-status {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 15px;
            background: #dcfce7;
            border: 1px solid #86efac;
            border-radius: 10px;
            margin-bottom: 25px;
        }
        
        .verification-icon {
            font-size: 24px;
        }
        
        .verification-text {
            flex: 1;
        }
        
        .verification-title {
            font-weight: 700;
            color: #15803d;
            margin-bottom: 3px;
        }
        
        .verification-desc {
            font-size: 13px;
            color: #16a34a;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="logo">
            <div class="logo-icon">🎉</div>
            <div class="logo-text">PlanSmart Ghana</div>
        </div>
        <div class="nav-right">
            <div class="vendor-badge">✓ VERIFIED VENDOR</div>
            <div class="user-avatar">AK</div>
        </div>
        
    </nav>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1>Edit Business Profile</h1>
            <p>Update your business information to attract more customers</p>
        </div>
        
        <!-- Verification Status -->
        <div class="verification-status">
            <div class="verification-icon">✅</div>
            <div class="verification-text">
                <div class="verification-title">Your business is verified!</div>
                <div class="verification-desc">Verified vendors get 3x more booking requests</div>
            </div>
        </div>
        
        <!-- Form Card -->
        <div class="form-card">
            <form>
                <!-- Basic Information -->
                <div class="form-section">
                    <h2 class="section-title">📋 Basic Information</h2>
                    
                    <div class="form-group">
                        <label class="form-label">Business Name <span class="required">*</span></label>
                        <input type="text" class="form-input" value="Ama's Kitchen" required>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Business Category <span class="required">*</span></label>
                            <select class="form-select" required>
                                <option>Catering Services</option>
                                <option>Photography</option>
                                <option>Decoration</option>
                                <option>Venue</option>
                                <option>Tent & Chairs Rental</option>
                                <option>Sound System</option>
                                <option>Transportation</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Years in Business</label>
                            <input type="number" class="form-input" value="15" placeholder="e.g., 5">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Business Description <span class="required">*</span></label>
                        <textarea class="form-input form-textarea" required>Traditional Ghanaian & continental dishes. 15+ years experience in large events. Specializing in weddings, funerals, and corporate functions.</textarea>
                        <p class="helper-text">Tell customers what makes your business special (min. 50 characters)</p>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="form-section">
                    <h2 class="section-title">📞 Contact Information</h2>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Phone Number <span class="required">*</span></label>
                            <input type="tel" class="form-input" value="024 123 4567" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">WhatsApp Number</label>
                            <input type="tel" class="form-input" value="024 123 4567">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Email Address <span class="required">*</span></label>
                            <input type="email" class="form-input" value="contact@amaskitchen.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Location <span class="required">*</span></label>
                            <select class="form-select" required>
                                <option>Greater Accra</option>
                                <option>Tema</option>
                                <option>Kumasi</option>
                                <option>Takoradi</option>
                                <option>Tamale</option>
                                <option>Cape Coast</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Service Details -->
                <div class="form-section">
                    <h2 class="section-title">🎯 Service Details</h2>
                    
                    <div class="form-group">
                        <label class="form-label">Event Types You Serve</label>
                        <div class="checkbox-group">
                            <div class="checkbox-item">
                                <input type="checkbox" id="wedding" checked>
                                <label for="wedding">💒 Weddings</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="funeral" checked>
                                <label for="funeral">🕊️ Funerals</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="naming" checked>
                                <label for="naming">👶 Naming Ceremonies</label>
                            </div>
                            <div class="checkbox-item">
                                <input type="checkbox" id="corporate">
                                <label for="corporate">💼 Corporate Events</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Minimum Guest Capacity</label>
                            <input type="number" class="form-input" value="50" placeholder="e.g., 50">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Maximum Guest Capacity</label>
                            <input type="number" class="form-input" value="500" placeholder="e.g., 500">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Starting Price (GHS) <span class="required">*</span></label>
                            <input type="number" class="form-input" value="5000" placeholder="e.g., 5000" required>
                            <p class="helper-text">Minimum price for your services</p>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Price per Person (Optional)</label>
                            <input type="number" class="form-input" value="30" placeholder="e.g., 30">
                            <p class="helper-text">If applicable to your service</p>
                        </div>
                    </div>
                </div>
                
                <!-- Images -->
                <div class="form-section">
                    <h2 class="section-title">📸 Business Images</h2>
                    
                    <div class="form-group">
                        <label class="form-label">Upload Photos of Your Work</label>
                        <div class="image-upload-area">
                            <div class="upload-icon">🖼️</div>
                            <div class="upload-text">Click to upload or drag and drop</div>
                            <div class="upload-hint">PNG, JPG up to 5MB (Max 10 photos)</div>
                        </div>
                    </div>
                </div>
                
                <!-- Form Actions -->
                <div class="form-actions">
                    <button type="button" class="btn-cancel">Cancel</button>
                    <button type="submit" class="btn-save">💾 Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
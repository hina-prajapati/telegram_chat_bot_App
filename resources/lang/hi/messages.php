<?php


// return [

//     // General
//     'language_select' => "🌐 कृपया अपनी भाषा चुनें",
//     'registration_welcome' => "💖 *LoveConnect में आपका स्वागत है!* 💖\n\nचलिए शुरुआत करते हैं, पहले हम आपको जान लेते हैं!",
//     'thank_you' => "✅ धन्यवाद!",
//     'invalid_input' => "❌ अमान्य उत्तर। कृपया सही जानकारी दर्ज करें।",

//     // Profile Questions
//     'ask_name' => "👤 *आपका नाम क्या है?*",
//     'thanks_name' => "✅ धन्यवाद, :name!",

//     'ask_email' => "📧 कृपया अपना ईमेल पता दर्ज करें।",
//     'thanks_email' => "✅ धन्यवाद! आपका ईमेल (:email) सहेज लिया गया है।",

//     'ask_gender' => "👩👨 कृपया अपना *लिंग* चुनें:",
//     'thanks_gender' => "✅ लिंग *:gender* के रूप में सहेजा गया।",
//     'gender_male' => "पुरुष",
//     'gender_female' => "महिला",
//     'gender_other' => "अन्य",

//     'ask_marital_status' => "💍 कृपया अपनी वैवाहिक स्थिति चुनें:",
//     'thanks_marital_status' => "✅ वैवाहिक स्थिति *:status* के रूप में सहेजी गई।",
//     'status_single' => "अविवाहित",
//     'status_married' => "विवाहित",
//     'status_divorced' => "तलाकशुदा",

//     'ask_dob' => "📅 कृपया अपनी जन्मतिथि `DD-MM-YYYY` प्रारूप में दर्ज करें:",
//     'dob_placeholder' => "DD-MM-YYYY",
//     'invalid_dob_format' => "❌ अमान्य प्रारूप। कृपया `DD-MM-YYYY` प्रारूप में दर्ज करें, जैसे *13-07-1998*.",
//     'thanks_dob' => "✅ आपकी जन्मतिथि *:dob* के रूप में सहेजी गई है।",

//     'ask_state' => "🏞️ कृपया अपना *राज्य* चुनें:",
//     'thanks_state' => "✅ राज्य *:state* के रूप में सहेजा गया है।",

//     'ask_city' => "🏙️ कृपया अपना *शहर* चुनें:",
//     'thanks_city' => "✅ शहर *:city* के रूप में सहेजा गया है।",
//     'select_state_first' => "कृपया पहले राज्य चुनें",

//     'ask_mother_tongue' => "🗣️ कृपया अपनी *मातृभाषा* चुनें:",
//     'thanks_mother_tongue' => "✅ *:tongue* सहेज लिया गया है। कृपया प्रतीक्षा करें...",
//     'tongue_hindi' => 'हिन्दी',
//     'tongue_marathi' => 'मराठी',
//     'tongue_gujarati' => 'गुजराती',
//     'tongue_punjabi' => 'पंजाबी',
//     'tongue_tamil' => 'तमिल',
//     'tongue_telugu' => 'तेलुगु',
//     'other' => 'अन्य',

//     'ask_religion' => "🙏 कृपया अपना *धर्म* चुनें:",
//     'thanks_religion' => "✅ धर्म *:religion* सहेजा गया है।",
//     'religion_hindu' => 'हिन्दू',
//     'religion_muslim' => 'मुस्लिम',
//     'religion_christian' => 'ईसाई',
//     'religion_sikh' => 'सिख',
//     'religion_buddhist' => 'बौद्ध',
//     'religion_jain' => 'जैन',
//     'other' => 'अन्य',

//     'ask_caste' => "🧬 कृपया अपनी *जाति* चुनें/टाइप करें:",
//     'thanks_caste' => "✅ जाति *:caste* के रूप में सहेजी गई है।",
//     'caste_brahmin' => 'ब्राह्मण',
//     'caste_kshatriya' => 'क्षत्रिय',
//     'caste_vaishya' => 'वैश्य',
//     'caste_shudra' => 'शूद्र',
//     'other' => 'अन्य',


//     'ask_education' => "🎓 कृपया अपनी *शिक्षा का उच्चतम स्तर* चुनें:",
//     'thanks_education' => "✅ शिक्षा स्तर *:education* के रूप में सहेजा गया है।",
//     'education_highschool' => 'हाई स्कूल',
//     'education_diploma' => 'डिप्लोमा',
//     'education_bachelor' => 'स्नातक',
//     'education_master' => 'स्नातकोत्तर',
//     'education_phd' => 'पीएचडी',
//     'other' => 'अन्य',


//     'ask_education_field' => "🔬 कृपया अपना *अध्ययन क्षेत्र* दर्ज करें (जैसे, इंजीनियरिंग, कला, वाणिज्य):",
//     'thanks_education_field' => "✅ अध्ययन क्षेत्र *:field* के रूप में सहेजा गया है।",
//     'field_engineering' => 'इंजीनियरिंग',
//     'field_arts' => 'कला',
//     'field_commerce' => 'वाणिज्य',
//     'field_science' => 'विज्ञान',
//     'other' => 'अन्य',

//     'ask_working_sector' => "💼 *कृपया अपना कार्य क्षेत्र चुनें:*",
//     'thanks_working_sector' => "✅ कार्य क्षेत्र *:sector* के रूप में सहेजा गया है।",
//     'sector_private' => 'निजी',
//     'sector_government' => 'सरकारी',
//     'sector_business' => 'व्यवसाय',
//     'sector_freelance' => 'फ्रीलांस',
//     'sector_student' => 'छात्र',
//     'sector_not_working' => 'काम नहीं कर रहा/रही',


//     'ask_profession' => "💼 *कृपया अपना पेशा दर्ज करें* (जैसे: सॉफ्टवेयर इंजीनियर, डॉक्टर, शिक्षक):",
//     'thanks_profession' => "✅ पेशा *:profession* के रूप में सहेजा गया है।",

//     'profession_software_engineer' => 'सॉफ्टवेयर इंजीनियर',
//     'profession_doctor' => 'डॉक्टर',
//     'profession_teacher' => 'शिक्षक',
//     'profession_businessman' => 'व्यवसायी',
//     'profession_student' => 'छात्र',
//     'profession_other' => 'अन्य',

//     'ask_mobile' => "📱 *कृपया अपना 10 अंकों का मोबाइल नंबर दर्ज करें* (6–9 से शुरू):",
//     'invalid_mobile' => "❌ अमान्य नंबर। कृपया *मान्य 10 अंकों* का मोबाइल नंबर दर्ज करें।",
//     'thanks_mobile' => "✅ मोबाइल नंबर *:mobile* के रूप में सहेजा गया है।",

//     'ask_profile_photo' => "📸 कृपया अपनी प्रोफ़ाइल फोटो एक इमेज अटैचमेंट के रूप में भेजें:",
//     'skip_photo' => 'फोटो छोड़ें',
//     'upload_another_photo' => 'एक और फोटो अपलोड करें',
//     'profile_photo_skipped' => "✅ समझ गया! चलिए अगले सवाल पर चलते हैं।",
//     'profile_photo_invalid_text' => "❌ कृपया एक वैध फोटो भेजें या *Done* टैप करें।",
//     'profile_photo_invalid' => "❌ कृपया एक वैध फोटो भेजें।",
//     'profile_photo_process_failed' => "❌ फोटो प्रोसेस नहीं हो सका।",
//     'profile_photo_save_failed' => "❌ फोटो सेव करने में असफल।",
//     'profile_photo_uploaded' => "🖼️ फोटो आपकी गैलरी में जोड़ दिया गया है!",

//     'ask_smoke' => "🚬 क्या आप *धूम्रपान* करते हैं?",
//     'saved_smoking' => "✅ धूम्रपान की प्राथमिकता *:value* के रूप में सहेजी गई है।",
//     'option_yes' => "हाँ",
//     'option_no' => "नहीं",
//     'option_occasionally' => "कभी-कभी",

//     'drinking_question' => "🍻 क्या आप *शराब पीते हैं*?",
//     'drinking_saved' => "✅ शराब पीने की पसंद *:value* के रूप में सहेजी गई है।",
//     'yes' => 'हाँ',
//     'no' => 'नहीं',
//     'occasionally' => 'कभी-कभी',

//     'ask_height' => "📏 अपनी *ऊंचाई* सेंटीमीटर में दर्ज करें (जैसे 170):",
//     'height_invalid' => "❌ कृपया मान्य ऊंचाई दर्ज करें (जैसे 170 सेमी)।",
//     'height_saved' => "✅ ऊंचाई *:value* सेमी के रूप में सहेजी गई है।",

//     'ask_body_type' => "🏋️‍♂️ आपकी *शारीरिक बनावट* क्या है?",
//     'body_type_saved' => "✅ शारीरिक बनावट *:value* के रूप में सहेजी गई है।",
//     'body_type_slim' => 'पतला',
//     'body_type_athletic' => 'एथलेटिक',
//     'body_type_average' => 'औसत',
//     'body_type_heavy' => 'भारी',

//     'ask_skin_tone' => "🧜‍♀️ कृपया अपनी *त्वचा की रंगत* चुनें:",
//     'skin_tone_saved' => "✅ त्वचा की रंगत *:value* के रूप में सहेजी गई है।",
//     'ask_life_partner_intro' => "हमें बताएं कि आप अपने जीवनसाथी को कैसे देखना चाहते हैं।",
//     'skin_fair' => 'गोरा',
//     'skin_wheatish' => 'गेहुँआ',
//     'skin_dusky' => 'सांवला',
//     'skin_dark' => 'काला',


//     'ask_partner_marital_status' => "💍 आपके *आवश्यक जीवनसाथी की वैवाहिक स्थिति* क्या होनी चाहिए?",
//     'partner_marital_status_saved' => "✅ जीवनसाथी की वैवाहिक स्थिति *:value* के रूप में सहेजी गई है।",
//     'status_single' => 'अविवाहित',
//     'status_divorced' => 'तलाकशुदा',
//     'status_widowed' => 'विधवा/विधुर',


//     'ask_partner_caste' => "🙏 कृपया अपने *पसंदीदा जीवनसाथी की जाति* चुनें:",
//     'partner_caste_saved' => "✅ पसंदीदा जीवनसाथी की जाति *:value* के रूप में सहेजी गई है।",
//     'caste_hindu' => 'हिंदू',
//     'caste_muslim' => 'मुस्लिम',
//     'caste_christian' => 'ईसाई',
//     'caste_sikh' => 'सिख',
//     'caste_jain' => 'जैन',
//     'caste_buddhist' => 'बौद्ध',
//     'caste_other' => 'अन्य',


//     'ask_partner_min_age' => "🎂 अपने साथी के लिए *न्यूनतम आयु* क्या होनी चाहिए?",
//     'partner_min_age_saved' => "✅ पसंदीदा न्यूनतम आयु *:value* के रूप में सहेजी गई है।",
//     'example_age' => 'उदा., 25',

//     'partner_max_age_question' => "🎂 अपने साथी के लिए *अधिकतम पसंदीदा आयु* क्या है?",
//     'partner_max_age_saved' => "✅ अधिकतम आयु *:value* के रूप में सहेजी गई है।",
//     'partner_max_age_invalid' => "❌ कृपया 18 से 100 के बीच की एक वैध आयु दर्ज करें।",

//     'partner_min_height_question' => "📏 अपने साथी की *न्यूनतम पसंदीदा ऊंचाई* क्या है?",
//     'partner_min_height_saved' => "✅ न्यूनतम ऊंचाई *:value* के रूप में सहेजी गई है।",


//     'partner_max_height_question' => "📏 अपने साथी की *अधिकतम पसंदीदा ऊंचाई* क्या है?",
//     'partner_max_height_saved' => "📏 अधिकतम ऊंचाई *:value* के रूप में सहेजी गई है।",

//     'partner_gender_question' => "👫 आपके *पसंदीदा जीवनसाथी का लिंग* क्या है?",
//     'partner_gender_saved' => "✅ जीवनसाथी का लिंग *:value* के रूप में सहेजा गया है।",

//     'partner_language_question' => "🌐 आपके जीवनसाथी की *पसंदीदा भाषा* क्या है?",


// ];
return [

    'language_select' => "🌐 कृपया अपनी भाषा चुनें",
    'registration_welcome' => "💖 *LoveConnect में आपका स्वागत है!* 💖\n\nचलिए शुरुआत करते हैं, पहले हम आपको जान लेते हैं!",
    'ask_name' => "👉 *आपका नाम क्या है?*",
    'thanks_name' => "✅ धन्यवाद, :name!",

    'ask_bio' => "📝 कृपया अपने बारे में एक छोटा *परिचय* लिखें (अधिकतम 255 अक्षर):",
    'thanks_bio' => "✅ धन्यवाद! आपका परिचय सहेजा गया है:\n\n:bio",
    'bio_too_long' => "❌ आपका परिचय बहुत लंबा है। कृपया :max अक्षरों तक सीमित करें।",

    'ask_email' => "📧 कृपया अपना ईमेल पता दर्ज करें।",
    'thanks_email' => "✅ धन्यवाद! आपका ईमेल (:email) सहेज लिया गया है।",

    'ask_gender' => "👩👨 कृपया अपना *लिंग* चुनें:",
    'thanks_gender' => "✅ लिंग *:gender* के रूप में सहेजा गया है।",
    'gender_male' => "पुरुष",
    'gender_female' => "महिला",
    'gender_other' => "अन्य",

    'ask_marital_status' => "💍 *कृपया अपनी वैवाहिक स्थिति चुनें:*",
    'thanks_marital_status' => "✅ वैवाहिक स्थिति *:status* के रूप में सहेजी गई है।",
    'status_single' => "अविवाहित",
    'status_married' => "विवाहित",
    'status_divorced' => "तलाकशुदा",
    'status_any' => "कोई भी",

    'ask_dob' => "📅 *कृपया अपनी जन्मतिथि `DD-MM-YYYY` प्रारूप में दर्ज करें:*",
    'dob_placeholder' => "DD-MM-YYYY",
    'invalid_dob_format' => "❌ अमान्य प्रारूप। कृपया `DD-MM-YYYY` प्रारूप में दर्ज करें, जैसे *13-07-1998*.",
    'thanks_dob' => "✅ जन्मतिथि *:dob* के रूप में सहेजी गई है।",

    'ask_state' => "🏞️ *कृपया अपना राज्य चुनें:*",
    'thanks_state' => "✅ राज्य *:state* के रूप में सहेजा गया है।",

    'ask_city' => "🏙️ *कृपया अपना शहर चुनें:*",
    'thanks_city' => "✅ शहर *:city* के रूप में सहेजा गया है।",
    'select_state_first' => "कृपया पहले राज्य चुनें।",

    'ask_mother_tongue' => "🗣️ *कृपया अपनी मातृभाषा चुनें:*\n\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",
    'thanks_mother_tongue' => "✅ *:tongue* सहेजी गई है। कृपया प्रतीक्षा करें...",
    'tongue_hindi' => 'हिन्दी',
    'tongue_marathi' => 'मराठी',
    'tongue_gujarati' => 'गुजराती',
    'tongue_punjabi' => 'पंजाबी',
    'tongue_tamil' => 'तमिल',
    'tongue_telugu' => 'तेलुगू',

    'ask_religion' => "🙏 कृपया अपना *धर्म* चुनें:\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",
    'thanks_religion' => "✅ धर्म *:religion* के रूप में सहेजा गया है।",
    'religion_hindu' => 'हिन्दू',
    'religion_muslim' => 'मुस्लिम',
    'religion_christian' => 'ईसाई',
    'religion_sikh' => 'सिख',
    'religion_buddhist' => 'बौद्ध',
    'religion_jain' => 'जैन',

    'ask_caste' => "🧬 *कृपया अपनी जाति चुनें या टाइप करें:*\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",
    'thanks_caste' => "✅ जाति *:caste* के रूप में सहेजी गई है।",
    'caste_brahmin' => 'ब्राह्मण',
    'caste_kshatriya' => 'क्षत्रिय',
    'caste_vaishya' => 'वैश्य',
    'caste_shudra' => 'शूद्र',

    'ask_education' => "🎓 *कृपया अपनी उच्चतम शिक्षा स्तर चुनें:*\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",
    'thanks_education' => "✅ शिक्षा स्तर *:education* के रूप में सहेजा गया है।",
    'education_highschool' => 'हाई स्कूल',
    'education_diploma' => 'डिप्लोमा',
    'education_bachelor' => 'स्नातक',
    'education_master' => 'स्नातकोत्तर',
    'education_phd' => 'पीएचडी',

    'ask_education_field' => "🔬 *कृपया अपने अध्ययन का क्षेत्र दर्ज करें* (उदाहरण: इंजीनियरिंग, कला, वाणिज्य):\nयदि सूची में नहीं है, तो मैन्युअली टाइप करें।",
    'thanks_education_field' => "✅ अध्ययन क्षेत्र *:field* के रूप में सहेजा गया है।",
    'field_engineering' => 'इंजीनियरिंग',
    'field_arts' => 'कला',
    'field_commerce' => 'वाणिज्य',
    'field_science' => 'विज्ञान',

    'ask_job_status' => '💼 *कृपया अपनी वर्तमान नौकरी की स्थिति चुनें:*',
    'thanks_job_status' => '✅ आपकी नौकरी की स्थिति *:status* के रूप में सहेजी गई है।',
    'job_employed' => 'नियोजित',
    'job_self_employed' => 'स्वरोज़गार',
    'job_student' => 'छात्र',
    'job_unemployed' => 'बेरोजगार',
    'job_service' => 'सेवा/नौकरी',
    'job_business' => 'व्यवसाय',
    'job_home_business' => 'होम-बेस्ड व्यवसाय',
    'job_house_maker' => 'गृहिणी',

    'ask_working_sector' => "💼 *कृपया अपना कार्य क्षेत्र चुनें:*\nयदि सूची में नहीं है, तो कृपया मैन्युअली टाइप करें।",
    'thanks_working_sector' => "✅ कार्य क्षेत्र *:sector* के रूप में सहेजा गया है।",
    'sector_private' => 'निजी',
    'sector_government' => 'सरकारी',
    'sector_business' => 'व्यवसाय',
    'sector_freelance' => 'फ्रीलांस',
    'sector_student' => 'छात्र',
    'sector_not_working' => 'काम नहीं कर रहे',

    'ask_profession' => "💼 *कृपया अपना पेशा दर्ज करें* (जैसे: सॉफ्टवेयर इंजीनियर, डॉक्टर, शिक्षक)।",
    'thanks_profession' => "✅ पेशा *:profession* के रूप में सहेजा गया है।",
    'profession_software_engineer' => 'सॉफ्टवेयर इंजीनियर',
    'profession_doctor' => 'डॉक्टर',
    'profession_teacher' => 'शिक्षक',
    'profession_businessman' => 'व्यवसायी',
    'profession_student' => 'छात्र',
    'profession_house_maker' => 'गृहिणी',

    'ask_mobile' => "📱 *कृपया अपना 10 अंकों का मोबाइल नंबर दर्ज करें* (6–9 से शुरू होने वाला):",
    'invalid_mobile' => "❌ अमान्य नंबर। कृपया *मान्य 10 अंकों* का मोबाइल नंबर दर्ज करें।",
    'thanks_mobile' => "✅ मोबाइल नंबर *:mobile* के रूप में सहेजा गया है।",

    'ask_profile_photo' => "📸 *कृपया अपनी प्रोफ़ाइल फ़ोटो भेजें* एक इमेज के रूप में:",
    'skip_photo' => 'फोटो छोड़ें',
    'upload_another_photo' => 'एक और फोटो अपलोड करें',
    'profile_photo_skipped' => "✅ समझ गया! चलिए आगे बढ़ते हैं।",
    'profile_photo_invalid_text' => "❌ कृपया एक वैध फोटो भेजें या *Done* दबाएं।",
    'profile_photo_invalid' => "❌ कृपया एक वैध फोटो भेजें।",
    'profile_photo_process_failed' => "❌ फोटो प्रोसेस नहीं हो सका।",
    'profile_photo_save_failed' => "❌ फोटो सेव करने में विफल।",
    'profile_photo_uploaded' => "🖼️ फोटो गैलरी में जोड़ दिया गया है!",

    'ask_diet' => '🍽️ *आपका भोजन विकल्प क्या है?*',
    'invalid_diet' => '❌ कृपया शाकाहारी, मांसाहारी या दोनों में से चुनें।',
    'saved_diet' => '✅ भोजन विकल्प *:diet* के रूप में सहेजा गया है।',

    'ask_smoke' => "🚬 क्या आप *धूम्रपान* करते हैं?",
    'saved_smoking' => "✅ धूम्रपान की प्राथमिकता *:value* के रूप में सहेजी गई है।",
    'option_yes' => "हाँ",
    'option_no' => "नहीं",
    'option_occasionally' => "कभी-कभी",

    'drinking_question' => "🍻 क्या आप *शराब पीते हैं*?",
    'drinking_saved' => "✅ शराब पीने की प्राथमिकता *:value* के रूप में सहेजी गई है।",
    'yes' => 'हाँ',
    'no' => 'नहीं',
    'occasionally' => 'कभी-कभी',

    'ask_height' => "📏 *अपनी ऊंचाई दर्ज करें* फीट और इंच में (जैसे: 5.2 मतलब 5 फीट 2 इंच)",
    'height_invalid' => "❌ अमान्य ऊंचाई। कृपया 5.2 जैसे प्रारूप में दर्ज करें।",
    'height_saved' => "✅ ऊंचाई *:value* के रूप में सहेजी गई है।",

    'ask_body_type' => "🏋️‍♂️ आपकी *शारीरिक बनावट* क्या है?",
    'body_type_saved' => "✅ शारीरिक बनावट *:value* के रूप में सहेजी गई है।",
    'body_type_slim' => 'पतला',
    'body_type_athletic' => 'एथलेटिक',
    'body_type_average' => 'औसत',
    'body_type_heavy' => 'भारी',

    'ask_skin_tone' => "🧜‍♀️ कृपया अपनी *त्वचा की रंगत* चुनें:",
    'skin_tone_saved' => "✅ त्वचा की रंगत *:value* के रूप में सहेजी गई है।",
    'ask_life_partner_intro' => "हमें बताएं कि आप अपने भविष्य के जीवनसाथी को कैसे देखना चाहते हैं।",
    'skin_fair' => 'गोरा',
    'skin_wheatish' => 'गेहूंवर्णी',
    'skin_dusky' => 'सांवला',
    'skin_dark' => 'काला',

    'ask_partner_marital_status' => "💍 आपके *पसंदीदा जीवनसाथी की वैवाहिक स्थिति* क्या होनी चाहिए?\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",
    'partner_marital_status_saved' => "✅ जीवनसाथी की वैवाहिक स्थिति *:value* के रूप में सहेजी गई है।",
    'status_widowed' => 'विधवा/विधुर',

    'ask_partner_caste' => "🙏 कृपया अपने *पसंदीदा जीवनसाथी की जाति* चुनें:\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",
    'partner_caste_saved' => "✅ पसंदीदा जाति *:value* के रूप में सहेजी गई है।",
    'caste_any' => 'कोई भी',

    'ask_partner_min_age' => "🎂 आपके साथी की *न्यूनतम पसंदीदा आयु* क्या होनी चाहिए?",
    'partner_min_age_saved' => "✅ न्यूनतम आयु *:value* के रूप में सहेजी गई है।",
    'example_age' => 'उदाहरण: 25',

    'partner_max_age_question' => "🎂 आपके साथी की *अधिकतम पसंदीदा आयु* क्या है?",
    'partner_max_age_saved' => "✅ अधिकतम आयु *:value* के रूप में सहेजी गई है।",
    'partner_max_age_invalid' => "❌ कृपया 18 से 100 के बीच की मान्य आयु दर्ज करें।",

    // 'partner_min_height_question' => "📏 आपके साथी की *न्यूनतम ऊंचाई* क्या होनी चाहिए?",
    // 'partner_min_height_saved' => "✅ न्यूनतम ऊंचाई *:value* के रूप में सहेजी गई है।",

    'partner_min_height_question' => "📏 आपके साथी की *न्यूनतम पसंदीदा ऊँचाई* क्या है?\nकृपया 5.2 जैसे मान दर्ज करें (उदा: 5 फीट 2 इंच)।",
    'partner_min_height_saved' => "✅ न्यूनतम ऊँचाई *:value* के रूप में सहेजी गई है।",
    'height_invalid' => "❌ कृपया 5.2 (5 फीट 2 इंच) जैसे मान्य ऊँचाई दर्ज करें।",


    // 'partner_max_height_question' => "📏 आपके साथी की *अधिकतम ऊंचाई* क्या होनी चाहिए?",
    // 'partner_max_height_saved' => "📏 अधिकतम ऊंचाई *:value* के रूप में सहेजी गई है।",
    'partner_max_height_question' => "📏 आपके साथी की *अधिकतम पसंदीदा ऊंचाई* क्या है?\nकृपया उदाहरण के लिए 5.2 जैसा दर्ज करें।",
    'partner_max_height_saved' => "📏 अधिकतम ऊंचाई *:value* के रूप में सहेजी गई है।",
    'height_invalid' => "❌ कृपया वैध ऊंचाई दर्ज करें जैसे 5.2 (5 फीट 2 इंच)।",


    'partner_language_question' => "🌐 आपके जीवनसाथी की *पसंदीदा मातृभाषा* क्या होनी चाहिए?\nयदि सूची में नहीं है, तो कृपया मैन्युअली दर्ज करें।",

    'profile_already_complete' => '✅ आपकी प्रोफ़ाइल पहले से पूरी है! आप किसी भी समय /start टाइप करके इसे देख या संपादित कर सकते हैं।',

];
